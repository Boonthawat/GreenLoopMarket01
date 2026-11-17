 <?php
 require 'init.php';
require 'controller/recycle.php';
head("แต้มของฉัน");
?>
<div class="hero mb-4">
    <div>
        <span class="fs-3 lh-sm">นำอุปกรณ์อิเล็กทรอนิกส์ที่ไม่ได้มารีไซเคิลที่นี่ได้เลย</span>
        <p class="fs-5"></p>
        <h2 class="fs-1">สะสมแล้ว <?= $u['points']?> แต้ม</h2>
        <small></small>
    </div>
</div>
<form action="" class="card card-body border-0" method="post" enctype="multipart/form-data">
    <div class="row g-3">
        <aside class="col-md-4 d-flex justify-content-center flex-column p-4"> 
            <div class="fs-1">♻️</div>
            <p class="stext-succes fw-bold">RECYCLE GET POINT</p>
            <h3 class="text-success fw-bold">เริ่มต้นจากสิ่งเล็กๆ</h3>
            <p></p>
            <div>✨ ได้รับแต้มเมื่อรีไซเคิลสำเร็จ ✨</div>
        </aside>    
        <div class="col-md-8 p-4 ">
            <h2 class="fw-bold">เลือกหมวดหมู่และกรอกข้อมูลได้เลย!</h2>
            <small class="text-muted"></small>
            <label for="" class="form-label mt-2">หมวดหมู่</label>
            <select name="category_id" id="recycle-category" class="form-select" required>
                <option value="" selected disabled>เลือกหมวดหมู่</option>
                    <?php foreach($cats as $c): ?>
                        <option value="<?= $c['id']?>" data-factor="<?= $c['co2_per_kg']?>">
                            <?= $c['name']?> ลดได้ ( <?= $c['co2_per_kg']?> kgCO2e ต่อ กก. )
                        </option>
                    <?php endforeach;?>
                </option>
            </select>
            <label for="" class="form-label mt-2">รายการ</label>
            <input type="text" class="form-control" name="items_name">
            <label for="" class="form-label mt-2">น้ำหนัก</label>
            <input type="number" class="form-control" name="weight_kg" min="1" steps="1">
            <label for="" class="form-label mt-2">วิธีการขนส่ง</label>
            <select name="delivery_type" id="" class="form-select">
                <option value="">เลือกหมวดหมู่</option>
                <option value="self">นำส่งเอง</option>
                <option value="pickup">นัดรับที่บ้าน</option>
            </select>
            <label for="" class="form-label mt-2">ที่อยู่</label>
            <textarea name="address" id="" class="form-control" maxlength="500"></textarea>
            <div>
                <p id="recycle-estimate" class="text-succes small">เลือกหมวดหมู่เพื่อคำนวณแต้ที่ลดคาร์บอนได้</p>
                <label for="" class="form-label mt-2">รูปภาพสิ่งที่ต้องการรีไซเคิล</label>
                <input type="file" class="form-control mt-2" name="image"accept="png.,fpeg.,jpg.,">
                <button class="btn btn-success mt-4" name="" type="submit">ส่งคำขอ</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function(){
        const = $recycleCategory = $('recycle-category');
        const = $recycleWeight = $('recycle-weight');
        const = $recycleDisplay = $('recycle-estimate');

        const = updateEstimate = () => {
            const = factor = parseFloat($category.find(':select').data('factor')) || 0;
            const = weight = parseFloat($weight.val()) || 0;
            if (!factor || weight >= 0){
                recycleDisplay.text('เลือกหมวดหมู่เพื่อคำนวณแต้ที่ลดคาร์บอนได้');
                return;
            }
            const totalEstimate = factor * weight;
            recycleDisplay.text(`ลดได้ ${$totalEstimate.toFixe(2)}`);
            return;
        }
        $category.on('change', updateEstimate);
        $weight.on('input', updateEstimate);
    })
</script>
<?php foot() ?>