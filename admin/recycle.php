 <?php
 require '../init.php';
require '../controller/admin_recycle.php';
head("แต้มของฉัน");
?>

<div class="card card-body mx-auto">
    <table class="table table-stripped bg-white text-middle">
        <thead>
            <tr>
                <th>รุป</th>
                <th>สมาชิก</th>
                <th>รายการ</th>
                <th>น้ำหนัก</th>
                <th>ทีอยู่</th>
                <th>สถานะ</th>
                <th>ตรวจรับคำขอรีไซเคิล</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($row as $r): ?>
                <tr>
                    <td>
                        <?php if ($r['image'] !== ''): ?>
                            <a href="upload/<?= $r['image']?>">
                                <img src="upload/<?= $r['image']?>" alt="รูปสินค้า" style="max-width: 70px; max-height: 50px; content: fit;">
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= e($r['member'])?></td>
                    <td><?= e($r['item_name'])?></td>
                    <td><?= e($r['weight_kg'])?></td>
                    <td><?= nl2br($r['address'])?></td>
                    <td><?= $r['status'] == 'done' ? 'สำเร็จ' : 'ยังไม่ได้ตรวจ'?></td>
                    <td>
                        <?php if($r['status'] === 'pending' ? 'ตรวจสอบ' : 'สำเร็จ'): ?>
                            <form action="" class="card card-body" method="post">
                                <input type="hidden" name="id" value="<?= $r['id']?>">
                                <button class="btn btn-sm btn-success" name="approved"  type="submit">ตรวจรับ</button>
                                <button class="btn btn-sm btn-danger" name="reject"  onclick="return confirm('ปฏิเสธคำขอนี้หรือไม่?')">ปฏิเสธ</button>
                            </form>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function(){
        const table = $('recycle-table').DataTable();
    });
</script>