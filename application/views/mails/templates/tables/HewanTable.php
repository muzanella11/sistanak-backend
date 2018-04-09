<table class="content__table">
    <tr class="theadMe">
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Nama
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            Deskripsi
        </td>
    </tr>

    <?php
        if (count($contentTable) > 0) {
            foreach ($contentTable as $key => $value) {
    ?>
    <tr class="tableContent">
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo $value['name']; ?>
        </td>
        <td class="td" style="width: 12%; padding: 10px 10px;">
            <?php echo $value['description']; ?>
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