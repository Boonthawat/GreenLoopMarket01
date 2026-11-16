<?php
require 'init.php';
require 'controller/forgot.php';
head('ลืมรหัสผ่าน');
?>
<div class="contianer py-4">
    <form action="" class="card card-body mx-auto" method="post" style="max-width: 540px;">
        <label for="" class="form-label mt-2">อีเมล</label>
        <input type="email" name="email" class="form-control" required>
        <label for="" class="form-label mt-2">คำถามกันลืมรหัสผ่าน</label>
        <select name="question" id="" class="form-select" required>
            <option value="">เลือกคำถาม</option>
            <option value="สัตว์เลี้ยง">สัตว์เลี้ยงตัวแรกของคุณชื่อ</option>
        </select>
        <label for="" class="form-label mt-2">คำตอบ</label>
        <input type="text" name="answer" class="form-control" required>
        <label for="" class="form-label mt-2">รหัสผ่านใหม่ (อย่างน้อย 8 ตัวอักษร)</label>
        <input type="password" name="password" id="1" class="form-control" minlength="8" required >
        <label for="" class="form-label mt-2">ยืนยันรหัสผ่านใหม่</label>
        <input type="password" name="password2" id="2" class="form-control" required >
        <button class="btn btn-success mt-4" type="submit" name="change">เปลี่ยนรหัสผ่าน</button>
    </form>
</div>