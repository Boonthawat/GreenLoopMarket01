 <?php
 require '../init.php';
require '../controller/admin_user.php';
head("user");
?>


<div class="card card-body p-4">
    <table id="user_table" class="table table-stripped bg-white text-middle">
        <thead>
            <tr>
                <th>ฃื่อ</th>
                <th>เบอร์</th>
                <th>แต้ม</th>
                <th>สภานะ</th>
                <th>เปลี่ยนสภานะ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($row as $r): ?>
                 <tr>
                                    <td><?= e($r['name'])?></td>
                                    <td><?= $r['points']?></td>
                                    <td><?= e($r['phone'])?></td>
                                    <td><?= $r['status'] === 'active' ? 'ใช้งานได้' : 'ระงับสิทธ์' ?></td>
                                    <td>
                                        <form method="post" onsubmit="return confirm('ยืนยันเปลี่ยนสถานะ?')">
                                            <input type="hidden" name="id" value="<?= $r['id']?>">
                                            <button class="btn btn-sm btn-outline-<?=$r['status'] == 'active' ? 'danger' : 'success'?>" name="toggle_status">
                                                <?= $r['status'] == 'active' ? 'ระงับสิทธ์' : 'ยกเลิกระงับสิทธ์' ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
        const table = $('#user_table').DataTable();
    });
</script>