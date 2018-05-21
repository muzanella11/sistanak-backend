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
            <?php echo isset($value->name) ? $value->name : '-'; ?>
        </td>
        <td class="td" style="width: 50%; padding: 10px 10px;">
            <?php echo isset($value->total_animal) ? $value->total_animal : '-'; ?>
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