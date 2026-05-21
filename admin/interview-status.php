<?php
session_start();
require 'db_connect.php'; 
//require 'db_connect_local.php'; 
// 1. "Deletion" using Prepared Statements interview-status.php  - should go to admin.aigeminicollege.org
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    // We only delete if status is inactive to maintain record integrity
    $delete_stmt = $conn->prepare("DELETE FROM sign_up WHERE id = :id AND account_status = 'inactive'");
    $delete_stmt->execute(['id' => $id]);
    header("Location: interview-status"); 
    exit();
}

// 2. Data Retrieval - PDO Query Execution
try {
    //$query = "SELECT id, last_name, email, phone_no, education, signup_date, account_status FROM sign_up ORDER BY signup_date DESC";
   $stmt = $pdo->query("SELECT id, last_name, email, phone_no, education, signup_date, account_status FROM sign_up ORDER BY signup_date DESC");
   // $stmt = $conn->query($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AGC Admin | Interview Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="bg-[#0B0D10] p-6 flex justify-between items-center">
            <h2 class="text-2xl font-black text-[#40E0FF] uppercase tracking-widest italic">AI GC Interview Status Report - Admin</h2>
            <span class="text-white font-mono text-xs bg-gray-800 px-5 py-1 rounded-full border border-gray-700">Monitoring Active</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                        <th class="p-4 border-b">ID</th>
                        <th class="p-4 border-b">Student Name</th>
                        <th class="p-4 border-b">Contact Info</th>
                        <th class="p-4 border-b">Education</th>
                        <th class="p-4 border-b">Signup Date</th>
                        <th class="p-4 border-b text-center">Status/Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr><td colspan="6" class="p-10 text-center text-gray-500 italic">No student records found in the foundry.</td></tr>
                    <?php else: ?>
                        <?php foreach($rows as $row): ?>
                        <tr class="hover:bg-blue-50/30 transition-colors border-b">
                            <td class="p-4 font-mono text-sm text-gray-600"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="p-4">
                                <div class="font-bold text-gray-900"><?php echo htmlspecialchars($row['last_name']); ?></div>
                                <div class="text-xs text-gray-500 uppercase tracking-tighter"><?php echo htmlspecialchars($row['account_status']); ?></div>
                            </td>
                            <td class="p-4 text-sm">
                                <div class="font-medium"><?php echo htmlspecialchars($row['email']); ?></div>
                                <div class="text-gray-500"><?php echo htmlspecialchars($row['phone_no']); ?></div>
                            </td>
                            <td class="p-4 text-sm">
                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-700"><?php echo htmlspecialchars($row['education'] ?? 'N/A'); ?></span>
                            </td>
                            <td class="p-4 text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($row['signup_date'])); ?>
                            </td>
                            <td class="p-4 text-center">
                                <?php if($row['account_status'] === 'inactive'): ?>
                                    <a href="?delete_id=<?php echo $row['id']; ?>" 
                                       onclick="return confirm('Sovereign Override: Confirm permanent deletion?')"
                                       class="bg-[#BC4A3C] text-white px-4 py-1.5 rounded-md text-xs font-black hover:bg-red-800 transition-all shadow-sm">
                                       DELETE
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        ACTIVE
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>