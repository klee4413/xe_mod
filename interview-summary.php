<?php
// =========================================================================
// GAC COGNITIVE FOUNDRY — TRANSACTION & MATRIX REPORT LAYER : interview-summary.php
// PURPOSE: INGEST INDIVIDUAL RESPONSES, COMPUTE SUMMARIES, POPULATE 4 SCHEMA TABLES
// =========================================================================
session_start();
require_once __DIR__ . '/../db-connect.php';
date_default_timezone_set('America/Los_Angeles');

// 0. AUTHENTICATION & DEMOGRAPHIC FALLBACK BOUNDARIES
$user_id = $_SESSION['user_id']    ?? 9990;
$first_name = $_SESSION['first_name'] ?? 'Scholar';
$last_name  = $_SESSION['last_name']  ?? '';
$email      = $_SESSION['email']  ?? '';
 
$current_time = date('Y-m-d H:i:s');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    // =========================================================================
    // 1. DATA PIPELINE TRANSACTION: RE-POPULATING DETAILED RESPONSES (TABLES 1 & 2)
    // =========================================================================
    if (isset($_SESSION['gac_selected_json']) && isset($_SESSION['gac_unselected_json'])) {
        
        // Parse the secure serialization payloads back out into server memory arrays
        $selected_raw = json_decode($_SESSION['gac_selected_json'], true) ?: [];
        $unselected_raw = json_decode($_SESSION['gac_unselected_json'], true) ?: [];

        // Truncate the detailed item repositories to prepare for fresh transaction blocks
        $pdo->exec("TRUNCATE TABLE `interview_sel`;");
        $pdo->exec("TRUNCATE TABLE `interview_unsel`;");

        // Hydrate interview_sel table
        if (!empty($selected_raw)) {
            $sel_stmt = $pdo->prepare("INSERT INTO `interview_sel` (`response`, `attribute`, `rank`, `importance`, `np`) VALUES (?, ?, ?, ?, ?)");
            foreach ($selected_raw as $item) {
                // Read from upstream interview table properties map context
                $query_stmt = $pdo->prepare("SELECT `rank` FROM `interview-response` WHERE `no` = ? LIMIT 1");
                $query_stmt->execute([intval($item['no'])]);
                $meta = $query_stmt->fetch();
                $rank_val = $meta ? intval($meta['rank']) : 5;

                $sel_stmt->execute([
                    htmlspecialchars_decode($item['quiz'], ENT_QUOTES),
                    $item['result'],
                    $rank_val,
                    intval($item['weight']),
                    $item['pn']
                ]);
            }
        }

        // Hydrate interview_unsel table
        if (!empty($unselected_raw)) {
            $unsel_stmt = $pdo->prepare("INSERT INTO `interview_unsel` (`response`, `attribute`, `rank`, `importance`, `np`) VALUES (?, ?, ?, ?, ?)");
            foreach ($unselected_raw as $item) {
                $query_stmt = $pdo->prepare("SELECT `rank` FROM `interview-response` WHERE `no` = ? LIMIT 1");
                $query_stmt->execute([intval($item['no'])]);
                $meta = $query_stmt->fetch();
                $rank_val = $meta ? intval($meta['rank']) : 5;

                $unsel_stmt->execute([
                    htmlspecialchars_decode($item['quiz'], ENT_QUOTES),
                    $item['result'],
                    $rank_val,
                    intval($item['weight']),
                    $item['pn']
                ]);
            }
        }

        // Clear session strings to prevent recursive submission on page refresh lines
        unset($_SESSION['gac_selected_json']);
        unset($_SESSION['gac_unselected_json']);
    }

    // =========================================================================
    // 2. SUMMARY ANALYSIS INGESTION PIPELINE (TABLES 3 & 4)
    // =========================================================================
    // Truncate factor matrices before loading compiled summaries
    $pdo->exec("TRUNCATE TABLE `temp_selected_factors`;");
    $pdo->exec("TRUNCATE TABLE `temp_unselected_factors`;");

    // Extract grouped records from detailed tables and insert into factors tables
    $pdo->exec("INSERT INTO `temp_selected_factors` (`attribute`, `selection_count`, `sum_public_rating`, `rating_avg`)
                SELECT `attribute`, COUNT(*), SUM(`importance`), ROUND(AVG(`importance`), 2) 
                FROM `interview_sel` GROUP BY `attribute`;");

    $pdo->exec("INSERT INTO `temp_unselected_factors` (`attribute`, `unselected_count`, `sum_public_rating`, `rating_avg`)
                SELECT `attribute`, COUNT(*), SUM(`importance`), ROUND(AVG(`importance`), 2) 
                FROM `interview_unsel` GROUP BY `attribute`;");

    // =========================================================================
    // 3. READ MATRIX DATA SETS FOR VIEWPORT RENDERING
    // =========================================================================
    // Detailed Lists
    $detailed_sel = $pdo->query("SELECT `no`, `attribute`, `rank`, `importance` FROM `interview_sel` ORDER BY `attribute` ASC")->fetchAll();
    $detailed_unsel = $pdo->query("SELECT `no`, `attribute`, `rank`, `importance` FROM `interview_unsel` ORDER BY `attribute` ASC")->fetchAll();

    // Summary Factor Lists
    $factors_sel = $pdo->query("SELECT `attribute`, `selection_count`, `sum_public_rating`, `rating_avg` FROM `temp_selected_factors` ORDER BY `attribute` ASC")->fetchAll();
    $factors_unsel = $pdo->query("SELECT `attribute`, `unselected_count`, `sum_public_rating`, `rating_avg` FROM `temp_unselected_factors` ORDER BY `attribute` ASC")->fetchAll();

} catch (PDOException $e) {
    die("Foundry Aggregation Execution Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Instant Deep Interview Analysis</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0fdf4; padding: 20px; color: #333; }
        .wrapper { max-width: 1100px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        /* Dashboard Header */
        .header-dash { border-bottom: 4px solid #66ff00; padding-bottom: 15px; margin-bottom: 35px; }
        .header-dash h1 { font-size: 1.8rem; color: #1b5e20; text-transform: uppercase; letter-spacing: -0.5px; }
        .meta-grid { display: flex; justify-content: space-between; font-family: monospace; font-size: 0.85rem; color: #555; margin-top: 5px; }
        .meta-grid span strong { color: #2e7d32; }

        h2 { font-size: 1.2rem; margin-bottom: 12px; display: inline-block; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; font-weight: 700; }
        .title-sel { background-color: #e8f5e9; color: #1b5e20; border-left: 4px solid #2e7d32; }
        .title-unsel { background-color: #ffebee; color: #b71c1c; border-left: 4px solid #c62828; margin-top: 40px; }

        /* Pure CRUD Table Layout definitions */
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 0.9rem; }
        th { padding: 10px; color: white; text-align: center; font-weight: bold; }
        td { padding: 10px; border: 1px solid #e2e8f0; text-align: center; }
        tr:nth-child(even) { background-color: #f8fafc; }
        
        .th-sel { background-color: #2e7d32; }
        .th-unsel { background-color: #475569; }
        .txt-left { text-align: left; }
        
        .footer-totals { font-weight: bold; background-color: #f1f5f9 !important; border-top: 2px solid #cbd5e1; }
        .footer-totals td { color: #0f172a; }

        /* Actions interface navigation links */
        .action-dock { display: flex; gap: 20px; justify-content: center; margin-top: 45px; }
        .btn { padding: 14px 35px; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; font-weight: bold; text-decoration: none; transition: all 0.2s; text-transform: uppercase; }
        .btn-ai { background: #66ff00; color: #1b5e20; box-shadow: 0 4px 0 #2e7d32; }
        .btn-ai:hover { background: #55dd00; transform: translateY(2px); box-shadow: 0 2px 0 #2e7d32; }
        .btn-back { background: #e2e8f0; color: #334155; border: 1px solid #cbd5e1; }
        .btn-back:hover { background: #cbd5e1; }

        .gac-footer { background: #66ff00; padding: 12px; text-align: center; margin-top: 50px; font-weight: bold; color: #1b5e20; border-top: 2px solid #2e7d32; border-radius: 6px; font-size: 0.85rem; }
    </style>
</head>
<body>

<div class="wrapper">
    
    <header class="header-dash">
        <h1>Instant Deep Interview Analysis</h1>
        <div class="meta-grid">
            <span>Reviewee ID: <strong><?php echo htmlspecialchars($user_id); ?></strong></span>
            <span>Applicant: <strong>Name: <?php echo htmlspecialchars($last_name); ?></strong></span>
            <span>Compiled Timestamp: <strong><?php echo htmlspecialchars($current_time); ?></strong></span>
        </div>
    </header>

    <h2 class="title-sel">Selected Interview Responses</h2>
    <table>
        <thead>
            <tr>
                <th class="th-sel" style="width: 80px;">No.</th>
                <th class="th-sel txt-left">Cognitive Attribute</th>
                <th class="th-sel">Psychometric Rank Value</th>
                <th class="th-sel">Public Importance</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $tot_sel_rank = 0; $tot_sel_imp = 0;
            if (empty($detailed_sel)): 
            ?>
                <tr><td colspan="4">No statements selected for compilation tracking.</td></tr>
            <?php else: ?>
                <?php foreach ($detailed_sel as $index => $row): 
                    $tot_sel_rank += $row['rank'];
                    $tot_sel_imp += $row['importance'];
                ?>
                    <tr>
                        <td><b><?php echo $index + 1; ?></b></td>
                        <td class="txt-left"><?php echo htmlspecialchars($row['attribute']); ?></td>
                        <td><?php echo $row['rank']; ?></td>
                        <td><?php echo $row['importance']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="footer-totals">
                    <td colspan="2" style="text-align: right; padding-right: 20px;">SUM TOTALS:</td>
                    <td><?php echo $tot_sel_rank; ?></td>
                    <td><?php echo $tot_sel_imp; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


    <h2 class="title-unsel">Unselected Interview Responses</h2>
    <table>
        <thead>
            <tr>
                <th class="th-unsel" style="width: 80px;">No.</th>
                <th class="th-unsel txt-left">Cognitive Attribute</th>
                <th class="th-unsel">Psychometric Rank Value</th>
                <th class="th-unsel">Public Importance</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $tot_unsel_rank = 0; $tot_unsel_imp = 0;
            if (empty($detailed_unsel)): 
            ?>
                <tr><td colspan="4">No metrics recorded inside unselected trace paths.</td></tr>
            <?php else: ?>
                <?php foreach ($detailed_unsel as $index => $row): 
                    $tot_unsel_rank += $row['rank'];
                    $tot_unsel_imp += $row['importance'];
                ?>
                    <tr>
                        <td><b><?php echo $index + 1; ?></b></td>
                        <td class="txt-left"><?php echo htmlspecialchars($row['attribute']); ?></td>
                        <td><?php echo $row['rank']; ?></td>
                        <td><?php echo $row['importance']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="footer-totals">
                    <td colspan="2" style="text-align: right; padding-right: 20px;">SUM TOTALS:</td>
                    <td><?php echo $tot_unsel_rank; ?></td>
                    <td><?php echo $tot_unsel_imp; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


    <h2 class="title-sel" style="margin-top: 50px;">Summary of Selected Interview Responses</h2>
    <table>
        <thead>
            <tr>
                <th class="th-sel" style="width: 80px;">No.</th>
                <th class="th-sel txt-left">Aggregated Attribute</th>
                <th class="th-sel">Selection Count</th>
                <th class="th-sel">Sum of Public Rating</th>
                <th class="th-sel">Rating Avg.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $f_tot_sel_count = 0; $f_tot_sel_sum = 0;
            if (empty($factors_sel)): 
            ?>
                <tr><td colspan="5">No factor metrics summarized.</td></tr>
            <?php else: ?>
                <?php foreach ($factors_sel as $index => $row): 
                    $f_tot_sel_count += $row['selection_count'];
                    $f_tot_sel_sum += $row['sum_public_rating'];
                ?>
                    <tr>
                        <td><b><?php echo $index + 1; ?></b></td>
                        <td class="txt-left"><?php echo htmlspecialchars($row['attribute']); ?></td>
                        <td><?php echo $row['selection_count']; ?></td>
                        <td><?php echo $row['sum_public_rating']; ?></td>
                        <td style="font-weight: bold; color: #2e7d32;"><?php echo $row['rating_avg']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="footer-totals">
                    <td colspan="2" style="text-align: right; padding-right: 20px;">TOTAL OF SUMS:</td>
                    <td><?php echo $f_tot_sel_count; ?></td>
                    <td><?php echo $f_tot_sel_sum; ?></td>
                    <td><?php echo $f_tot_sel_count > 0 ? round(($f_tot_sel_sum / $f_tot_sel_count), 2) : '0.00'; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>


    <h2 class="title-unsel">Summary of Unselected Interview Responses</h2>
    <table>
        <thead>
            <tr>
                <th class="th-unsel" style="width: 80px;">No.</th>
                <th class="th-unsel txt-left">Aggregated Attribute</th>
                <th class="th-unsel">Unselected Count</th>
                <th class="th-unsel">Sum of Public Rating</th>
                <th class="th-unsel">Rating Avg.</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $f_tot_unsel_count = 0; $f_tot_unsel_sum = 0;
            if (empty($factors_unsel)): 
            ?>
                <tr><td colspan="5">No control factors summarized.</td></tr>
            <?php else: ?>
                <?php foreach ($factors_unsel as $index => $row): 
                    $f_tot_unsel_count += $row['unselected_count'];
                    $f_tot_unsel_sum += $row['sum_public_rating'];
                ?>
                    <tr>
                        <td><b><?php echo $index + 1; ?></b></td>
                        <td class="txt-left"><?php echo htmlspecialchars($row['attribute']); ?></td>
                        <td><?php echo $row['unselected_count']; ?></td>
                        <td><?php echo $row['sum_public_rating']; ?></td>
                        <td style="font-weight: bold; color: #b71c1c;"><?php echo $row['rating_avg']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="footer-totals">
                    <td colspan="2" style="text-align: right; padding-right: 20px;">TOTAL OF SUMS:</td>
                    <td><?php echo $f_tot_unsel_count; ?></td>
                    <td><?php echo $f_tot_unsel_sum; ?></td>
                    <td><?php echo $f_tot_unsel_count > 0 ? round(($f_tot_unsel_sum / $f_tot_unsel_count), 2) : '0.00'; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="action-dock">
        <a href="interview-guide.php" class="btn btn-back">&larr; Return to Guide</a>
        <a href="interview-ai-analysis.php" class="btn btn-ai">Detail AI Analysis &rarr;</a>
    </div>

</div>

<footer class="gac-footer">
    Copyright AI Gemini College 2026 - Applied HPAM Interview Evaluation Model by AIGC research
</footer>

</body>
</html>