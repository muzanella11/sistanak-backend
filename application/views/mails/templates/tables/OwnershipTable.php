<table class="content__table">
    <tr class="theadMe">
        <td class="td" style="width: 20%; padding: 10px 10px;">
            Nama
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            Provinsi
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            Kota/Kabupaten
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            Kecamatan
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            Alamat
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            Daftar Hewan
        </td>
    </tr>

    <?php
        if (count($contentTable) > 0) {
            foreach ($contentTable as $key => $value) {
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['fullname']) ? $value['fullname'] : '-'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['province_detail']['name']) ? $value['province_detail']['name'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['region_detail']['name']) ? $value['region_detail']['name'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['village_detail']['name']) ? $value['village_detail']['name'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['address']) ? $value['address'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php
                if (isset($value['animal_list']) && count($value['animal_list']) > 0) {
                    foreach ($value['animal_list'] as $key => $value) {
            ?>
                => Nama Hewan : <?php echo $value['animal_detail']['name']; ?> <br>
                => Banyaknya Hewan : <?php echo $value['amount']; ?> <br>
                -----------------------------------------------------------
            <?php
                    }
                } else {
            ?>
                Tidak ditemukan data
            <?php
                }
            ?>
        </td>
    </tr>
    <?php
            } 
        } else { 
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 100%;">
            Tidak ada data ditemukan
        </td>
    </tr>
    <?php } ?>
</table>

<!-- <div style="position: relative; text-align: center; font-size: 15px; font-weight: bold; letter-spacing: 1.5; margin: 25px 0; margin-top: 500px;">
    Total Data Hewan
</div>

<table class="content__table" style="width: 100%;">
    <tr class="theadMe">
        <td class="td" style="width: 50%; padding: 10px 10px;">
            Nama Hewan
        </td>
        <td class="td" style="width: 50%; padding: 10px 10px;">
            Jumlah Hewan
        </td>
    </tr>

    <?php
        if (count($contentTable) > 0) {
            foreach ($contentTable as $key => $value) {
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 50%; padding: 10px 10px;">
            asda
        </td>
        <td class="td" style="width: 50%; padding: 10px 10px;">
            asd
        </td>
    </tr>
    <?php
            } 
        } else { 
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 100%;">
            Tidak ada data ditemukan
        </td>
    </tr>
    <?php } ?>
</table> -->