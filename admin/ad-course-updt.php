<?php
// [TIMESTAMP: 2026-03-01] - AIGC ADMINISTRATIVE MASTER CONTROL
session_start();
require_once __DIR__ . '/../../db-connect.php';

// --- 1. THE CRUD LOGIC GATE ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            if ($_POST['action'] === 'create') {
                $sql = "INSERT INTO classes (class_id, class_name, tier, syllabus, status, credit_hour) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_POST['class_id'], $_POST['class_name'], $_POST['tier'], $_POST['syllabus'], $_POST['status'], $_POST['credit_hour']]);
            } elseif ($_POST['action'] === 'update') {
                $sql = "UPDATE classes SET class_id=?, class_name=?, tier=?, syllabus=?, status=?, credit_hour=? WHERE no=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$_POST['class_id'], $_POST['class_name'], $_POST['tier'], $_POST['syllabus'], $_POST['status'], $_POST['credit_hour'], $_POST['no']]);
            } elseif ($_POST['action'] === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM classes WHERE no = ?");
                $stmt->execute([$_POST['no']]);
            }
            header("Location: admin_course.php?success=1");
            exit();
        } catch (PDOException $e) { $error = $e->getMessage(); }
    }
}

// --- 2. DATA RETRIEVAL ---
$classes = $pdo->query("SELECT * FROM classes ORDER BY no DESC")->fetchAll();
$edit_class = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE no = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_class = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAC | Admin Course Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-4 md:p-10">

    <div class="max-w-7xl mx-auto">
        <header class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">AIGC COURCE MAINTENANCE</h1>
            <a href="course_list.php" class="text-xs font-bold text-green-600 hover:underline">View Public Catalog ➔</a>
        </header>

        <div class="bg-white rounded-3xl shadow-xl p-8 mb-10 border-t-8 border-green-600">
            <h2 class="text-xl font-black mb-6"><?php echo $edit_class ? 'Edit Class' : 'Add New Class'; ?></h2>
            <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <input type="hidden" name="action" value="<?php echo $edit_class ? 'update' : 'create'; ?>">
                <?php if ($edit_class): ?><input type="hidden" name="no" value="<?php echo $edit_class['no']; ?>"><?php endif; ?>

                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Class ID</label>
                    <input type="text" name="class_id" value="<?php echo $edit_class['class_id'] ?? ''; ?>" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Class Name</label>
                    <input type="text" name="class_name" value="<?php echo $edit_class['class_name'] ?? ''; ?>" required class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-green-500 outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl">
                        <option value="LOCKED" <?php echo ($edit_class['status'] ?? '') == 'LOCKED' ? 'selected' : ''; ?>>LOCKED</option>
                        <option value="UNLOCK" <?php echo ($edit_class['status'] ?? '') == 'UNLOCK' ? 'selected' : ''; ?>>UNLOCK</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Tier</label>
                    <input type="text" name="tier" value="<?php echo $edit_class['tier'] ?? 'Basic'; ?>" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Credit Hours</label>
                    <input type="number" name="credit_hour" value="<?php echo $edit_class['credit_hour'] ?? 3; ?>" class="w-full bg-gray-50 border border-gray-200 p-3 rounded-xl">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Full Syllabus (Long Format)</label>
                    <textarea name="syllabus" rows="5" required class="w-full bg-gray-50 border border-gray-200 p-4 rounded-xl focus:ring-2 focus:ring-green-500 outline-none"><?php echo $edit_class['syllabus'] ?? ''; ?></textarea>
                </div>
                <div class="md:col-span-3 flex gap-4">
                    <button type="submit" class="bg-green-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-green-700">
                        <?php echo $edit_class ? 'Update Logic' : 'Ground Class'; ?>
                    </button>
                    <?php if ($edit_class): ?>
                        <a href="admin_course.php" class="bg-gray-200 text-gray-600 px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest flex items-center">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <table class="w-full text-left">
                <thead class="bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="p-6">ID</th>
                        <th class="p-6">Name</th>
                        <th class="p-6">Status</th>
                        <th class="p-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($classes as $c): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-6 font-bold text-green-700"><?php echo $c['class_id']; ?></td>
                            <td class="p-6">
                                <div class="font-black text-gray-900"><?php echo $c['class_name']; ?></div>
                                <div class="text-[10px] text-gray-400 line-clamp-1 italic"><?php echo $c['syllabus']; ?></div>
                            </td>
                            <td class="p-6">
                                <span class="text-[10px] font-black px-3 py-1 rounded-full <?php echo $c['status'] == 'UNLOCK' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                    <?php echo $c['status']; ?>
                                </span>
                            </td>
                            <td class="p-6 text-right space-x-4">
                                <a href="?edit=<?php echo $c['no']; ?>" class="text-blue-500 font-black text-[10px] uppercase hover:underline">Edit</a>
                                <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="no" value="<?php echo $c['no']; ?>">
                                    <button type="submit" class="text-red-500 font-black text-[10px] uppercase hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
 <footer class="p-20 text-center text-gray-400 font-bold uppercase tracking-widest">
        &copy; AI Gemini College 2026
</html>