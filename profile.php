<?php
require 'init.php';
require 'controller/profile.php';
head("โปรไฟล์");
?>
<div class="container py-4">
    <div class="row g-3">
        <div class="col-6">
            <form action="" class="card card-body mx-auto" method="post">
                <h2 class="fs-1 text-center">🫂</h2>
            <label for="" class="form-label mt-2">ฃื่อ-นามสกุล</label>
            <input type="text" name="name" class="form-control" required value="<?= e($u['name'])?>">
            <label for="" class="form-label mt-2">อีเมล</label>
            <input type="email" name="email" class="form-control" required value="<?= e($u['email'])?>">
            <label for="" class="form-label mt-2">เบอร์โทรศัพท์</label>
            <input type="phone" name="phone" class="form-control"  pattern="0[0-9]{0,9}" title="ตัวเลข 10 หลักขึ้นต้นด้วย 0"required value="<?= e($u['phone'])?>">
            <button class="btn btn-success mt-4" name="save" type="submit">บันทึก</button>
            </form>
        </div>
        <div class="col-6">
            <form action="" class="card card-body mx-auto" method = "post">
                    <label for="" class="form-label mt-2">รหัสผ่านปัจจุบัน</label>
                    <input type="password" name="current" id="1" class="form-control" required >
                    <label for="" class="form-label mt-2">รหัสผ่านใหม่</label>
                    <input type="password" name="password1" class="form-control" minlength="8" required >
                    <label for="" class="form-label mt-2">ยืนยันรหัสผ่านใหม่</label>
                    <input type="password" name="password2" class="form-control" required >
                <button class="btn btn-success mt-4" name="change" type="submit">เปลี่ยนรหัสผ่าน</button>
            </form>
        </div>
    </div>
</div>
<?php foot() ?>