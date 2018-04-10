<table class="content__table">
    <tr class="theadMe">
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Nama
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Nik
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Email
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Username
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Telepon
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Role
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Alamat
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Status Penugasan
        </td>
    </tr>

    <?php
        if (count($contentTable) > 0) {
            foreach ($contentTable as $key => $value) {
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['name']) ? $value['name'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['nik']) ? $value['nik'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['email']) ? $value['email'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['username']) ? $value['username'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['phone']) ? $value['phone'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['user_role_detail']['name']) ? $value['user_role_detail']['name'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['address']) ? $value['address'] : '-'; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo isset($value['name']) ? $value['name'] : '-'; ?>
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