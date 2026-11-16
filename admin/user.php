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
                    <td><?= e($r['phone'])?></td>
                    <td><?= e($r['points'])?></td>
                    <td><?= $r['status'] == 'active' ? 'ใช้งานอยู่' : 'ระงับสิทธ์'?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="id" value="<?= $r['id']?>">
                            <button value="1" name='sus' class="btn btn-sm btn-danger">ระงับสิทธ์</button>
                        </form>
                        <form action="" method="post" class="mt-2">
                            <input type="hidden" name="2" value="<?= $r['id']?>">
                            <button value="2"  name='unsus'class="btn btn-sm btn-outline-success">ยกเลิกการระงับสิทธ์</button>
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