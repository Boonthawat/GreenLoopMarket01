 <?php
 require '../init.php';
require '../controller/admin_content.php';
head('แต้มของฉัน');

if(!empty($_GET['edit'])){
    $id = trim($_GET['edit']?? '');
    $row = $db->query("SELECT * FROM contents WHERE id = $id")->fetch_assoc();
    // var_dump($_POST);exit;
?>
<div class="container py-4">
    <form action="" class="card card-body mx-auto" method="post">
        <h3><?= $row['id'] ? 'แก้ไขเนื้อหา' : 'เพิ่มเนื้อหา'?></h3>
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <label for="" class="form-label mt-2">หัวข้อ</label>
        <input type="text" name="title" class="form-control" value="<?= e($row['title'])?>" required minlength="3">
        <label for="" class="fotm-label mt-2">เนื้อหา</label>
        <textarea name="body" id="" class="form-control" required maxlenth="500"><?= e($row['body'])?></textarea>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="" class="form-label mt-2">เริ่มแสดง</label>
                <input type="datetime-local" class="form-control" name="start_at" required value="<?e($local($row['start_at']))?>">
            </div>
            <div class="col-md-6">
                <label for="" class="form-label mt-2">สิ้นสุดการแสดงผล</label>
                <input type="datetime-local" class="form-control" name="end_at" required value="<?e($local($row['end_at']))?>">
            </div>
        </div>
            <input type="hidden" name="Id" value="<?= $id?>">
        <div class="mt-4">
            <button class="btn btn-success" name="editId" type="submit">บันทึก</button>
            <a href="admin/contentphp" class="btn btn-outline-secondary">ล้างฟอร์ม</a>
        </div>
    </form>
</div>
<?php
}else{
?>
    <form action="" class="card card-body mx-auto" method="post">
        <h3><?= $edit['id'] ? 'แก้ไขเนื้อหา' : 'เพิ่มเนื้อหา'?></h3>
        <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <label for="" class="form-label mt-2">หัวข้อ</label>
        <input type="text" name="title" class="form-control" value="<?= e($edit['title'])?>" required minlength="3">
        <label for="" class="fotm-label mt-2">เนื้อหา</label>
        <textarea name="body" id="" class="form-control" required maxlenth="500"><?= e($edit['body'])?></textarea>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="" class="form-label mt-2">เริ่มแสดง</label>
                <input type="datetime-local" class="form-control" name="start_at" required value="<?e($local($edit['start_at']))?>">
            </div>
            <div class="col-md-6">
                <label for="" class="form-label mt-2">สิ้นสุดการแสดงผล</label>
                <input type="datetime-local" class="form-control" name="end_at" required value="<?e($local($edit['end_at']))?>">
            </div>
        </div>
            <input type="hidden" name="Id" value="<?= $id?>">
        <div class="mt-4">
            <button class="btn btn-success" name="editId" type="submit">บันทึก</button>
            <a href="admin/contentphp" class="btn btn-outline-secondary">ล้างฟอร์ม</a>
        </div>
    </form>
<?php
}
?>
<div class="card card-body mt-4">
    <table id="content_table" class="table table-stripped bg-white text-middle">
        <thead>
            <tr>
                <th>หัวข้อ</th>
                <th>เริ่มแสดง</th>
                <th>สิ้นสุดการแสดง</th>
                <th class="text-center">แก้ไขข้อมูลเนื้อหา</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $r): ?>
                <tr>
                    <td><?= e($r['title'])?></td>
                    <td><?= $r['start_at'] ? date('d/m/y H:i', strtotime($r['start_at'])) : ''?></td>
                    <td><?= $r['end_at'] ? date('d/m/y H:i', strtotime($r['end_at'])) : ''?></td>

                    <td class="text-center">
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <a href="admin/content.php?edit=<?= $r['id']?>" class="btn btn-success" name="editId" type="button">แก้ไข</a>
                            <button class="btn btn-danger" name="delete" onclick="return confirm('ยืนยันที่จะลบเนื้อหานี้?')">ลบ</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>            
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
        const table = $('#content_table').DataTable();
    });
</script>