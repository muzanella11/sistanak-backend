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
            <?php echo $value['fullname']; ?>
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
                if (isset($value['animal_list'])) {
                    foreach ($value['animal_list'] as $key => $value) {
            ?>
                => Nama Hewan : <?php echo $value['animal_detail']['name']; ?> <br>
                => Banyaknya Hewan : <?php echo $value['amount']; ?> <br>
                *******************************
            <?php
                    }
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