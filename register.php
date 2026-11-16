<?php


 require 'init.php';

head("สมัครสมาชิก");
?>

<div class="container py-4">
    <form action="controller/register.php" method="post" class="card card-body mx-auto" onsubmit="return checkPassword()" style="max-width: 540px;">
        <label for="register-name" class="form-label mt-2">ชื่อ-นามสกุล</label>
        <input id="register-name" type="text" name="name" class="form-control" maxlength="200" required>
        <label for="" class="form-label mt-2">อีเมล</label>
        <input type="email" name="email" class="form-control" required >
        <label for="register-phone" class="form-label mt-2">เบอร์โทรศัพท์</label>
        <input id="register-phone" type="tel" name="phone" class="form-control" pattern="0[0-9]{9}" title="ตัวเลข 10 หลักขึ้นต้นด้วย 0" required>
        <label for="" class="form-label mt-2">รหัสผ่าน (อย่างน้อย 8 ตัวอักษร)</label>
        <input type="password" name="password" id="register-password" class="form-control" minlength="8" required>
        <label for="" class="form-label mt-2">ยืนยันรหัสผ่าน</label>
        <input type="password" name="password2" id="register-password-confirm" class="form-control" minlength="8" required>
        <select name="question" class="form-select mt-2" required>
            <option value="">เลือกคำถาม</option>
            <option value="สัตว์เลี้ยง">สัตว์เลี้ยงตัวแรกชื่ออะไร</option>
        </select>
        <label for="" class="form-label mt-2">คำตอบเมื่อลืมรหัสผาน</label>
        <input type="text" name="answer" class="form-control" required>
        <button class="btn btn-success mt-4" type="submit">สมัครสมาชิก</button>
    </form>
</div>
<script>
    function checkPassword(){
        if ($('#register-password').val() !== $('#register-password-confirm').val()) {
            alert('รหัสผ่านไม่ตรงกัน โปรดกรอกใหม่');
            return false;
        }
        return true;
    };
</script>