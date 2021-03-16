<table>
    <tr>
        <td></td>
        <td></td>
        <td align='right'><b>Tanggal Cetak Laporan :</b></td>
        <td><?=date('d-m-Y H:i:s');?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='right'><b>Lokasi :</b></td>
        <td><?=$lokasi;?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='right'><b>Tanggal Cek Suhu :</b></td>
        <td><?=date('d-m-Y', strtotime($tanggal)); ?></td>
    </tr>
</table>


<table>
    <tr>
        <td></td>
        <td></td>
        <td><b>Karyawan Aktif belum cek temperature</b></td>
    </tr>
</table>

<table>
<thead>
    <tr>
        <th><b>No</b></th>
        <th><b>NIK</b></th>
        <th><b>NAMA</b></th>
        <th><b>JABATAN</b></th>
    </tr>
</thead>
<tbody>
    <?php
        $i = 1;
        foreach ($data_aktif as $item):
    ?>
            <tr>
                <td align='right'><?=$i;?></td>
                <td ><?=$item->NIK?></td>
                <td ><?=$item->NAMA;?></td>
                <td ><?=preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $item->JABATAN); ?></td>
            </tr>
    <?php
            $i++;
        endforeach;
    ?>
</tbody>
</table>
<br/><br/>

<table>
    <tr>
        <td></td>
        <td></td>
        <td><b>Karyawan Aktif sudah cek temperature</b></td>
    </tr>
</table>
<table >
<thead>
    <tr>
        <th><b>No</b></th>
        <th><b>NIK</b></th>
        <th><b>NAMA</b></th>
        <th><b>JABATAN</b></th>
        <th><b>Temperature</b></th>
    </tr>
</thead>
<tbody>
    <?php
        $i = 1;
        foreach ($aktif_is_temper as $item):
    ?>
            <tr>
                <td align='right'><?=$i;?></td>
                <td ><?=$item['NIK']?></td>
                <td ><?=$item['NAMA'];?></td>
                <td ><?=preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $item['JABATAN']); ?></td>
                <td ><?=$item['TEMPER'];?></td>
            </tr>
    <?php
            $i++;
        endforeach;
    ?>
</tbody>
</table>
<br/><br/>

<table>
    <tr>
        <td></td>
        <td></td>
        <td><b>Bukan Karyawan Aktif</b></td>
    </tr>
</table>
<table>
<thead>
    <tr>
        <th><b>No</b></th>
        <th><b>NIK</b></th>
        <th><b>NAMA</b></th>
        <th><b>Temperature</b></th>
    </tr>
</thead>
<tbody>
    <?php
        $i = 1;
        foreach ($data_temper as $item):
    ?>
            <tr>
                <td align='right'><?=$i;?></td>
                <td ><?=$item['NIK']?></td>
                <td ><?=$item['NAMA'];?></td>
                <td ><?=$item['TEMPER'];?></td>
            </tr>
    <?php
            $i++;
        endforeach;
    ?>
</tbody>
</table>