<table class="content__table">
    <tr class="theadMe">
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Provinsi
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Kota/Kabupaten
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Kecamatan
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Alamat
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Drainase
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Hygiene
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Sumber Air
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Pencemaran
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Ketersediaan Sumber Makanan
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Luas Lahan
        </td>
    </tr>

    <?php
        if (count($contentTable) > 0) {
            foreach ($contentTable as $key => $value) {
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['province_detail']['name']) ? $value['province_detail']['name'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['region_detail']['name']) ? $value['region_detail']['name'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['village_detail']->name) ? $value['village_detail']->name : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['address']) ? $value['address'] : 'undefined'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['drainase']) && $value['drainase'] === 1 ? 'Ya' : 'Tidak'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['hygiene']) && $value['hygiene'] === 1 ? 'Ya' : 'Tidak'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['fount']) && $value['fount'] === 1 ? 'Ya' : 'Tidak'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['pollution']) && $value['pollution'] === 1 ? 'Ya' : 'Tidak'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['food_availability']) && $value['food_availability'] === 1 ? 'Ya' : 'Tidak'; ?>
        </td>
        <td class="td" style="width: 20%; padding: 10px 10px;">
            <?php echo isset($value['land_area'])? $value['land_area'].'m<sup>2</sup>' : 'undefined'; ?>
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