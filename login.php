<?php


 require 'init.php';

head("เข้าสู่ระบบ");
?>


<div class="container py-4">
    <form action="controller/login.php" class="card card-body mx-auto" method="post" style="max-width: 540px;"> 
        <label for="" class="form-label mt-2">อีเมล</label>
        <input type="email" name="email" class="form-control" required >
        <label for="" class="form-label mt-2">รหัสผ่าน</label>
        <input type="password" name="password" id="1" class="form-control" minlength="8" required>
        <button class="btn btn-success mt-4" type="submit">เข้าสู่ระบบ</button>
        <div class="mt-2 d-flex justify-content-between">
            <a href="forgot.php">ลืมรหัสผ่าน?</a>
            <a href="register.php">สมัครสมาชิก</a>
        </div>
    </form>
</div>