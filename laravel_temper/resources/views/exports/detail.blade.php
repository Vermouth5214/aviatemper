<table>
    <tr>
        <td></td>
        <td></td>
        <td align='right'>Tanggal Cetak Laporan :</td>
        <td><?=date('d-m-Y H:i:s');?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='right'>Lokasi :</td>
        <td><?=$lokasi;?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='right'>Tanggal Cek Suhu :</td>
        <td><?=date('d-m-Y', strtotime($tanggal)); ?></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Temperature</th>
            <th>Time</th>
            <th>Checked By</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 1;
            foreach ($data as $item):
        ?>
                <tr>
                    <td align='right' ><?=$i;?></td>
                    <td ><?=$item->name;?></td>
                    <td ><?=$item->temperature;?></td>
                    <td ><?=date('H:i:s', strtotime($item->created_at));?></td>
                    <td ><?=$item->created_by;?></td>
                </tr>
        <?php
                $i++;
            endforeach;
        ?>
    </tbody>
</table>