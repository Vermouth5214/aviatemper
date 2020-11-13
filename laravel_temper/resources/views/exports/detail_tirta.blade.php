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
        <td><?=$location;?></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='right'>Periode :</td>
        <td><?=date('d-m-Y', strtotime($startDate)); ?> - <?=date('d-m-Y', strtotime($endDate)); ?></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Temperatur</th>
            <th>Lokasi</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Dicek oleh</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $i = 1;
            foreach ($data as $item):
                $userinfo = Session::get('userinfo');
                if ((($userinfo['priv'] == 'VTTIRTA') || ($userinfo['priv'] == 'VHTIRTA')) && ($location == "PUSAT")){
                    if (in_array($item->name, $data_pusat)):
        ?>
                <tr>
                    <td align='right' ><?=$i;?></td>
                    <td ><?=$item->nik;?></td>
                    <td ><?=$item->name;?></td>
                    <td ><?=$item->temperature;?></td>
                    <td ><?=$item->lokasi;?></td>
                    <td ><?=date('d-M-Y', strtotime($item->created_at));?></td>
                    <td ><?=date('H:i:s', strtotime($item->created_at));?></td>
                    <td ><?=$item->created_by;?></td>
                </tr>
        <?php
                $i++;
            endif;
        } else {
        ?>
            <tr>
                <td align='right' ><?=$i;?></td>
                <td ><?=$item->nik;?></td>
                <td ><?=$item->name;?></td>
                <td ><?=$item->temperature;?></td>
                <td ><?=$item->lokasi;?></td>
                <td ><?=date('d-M-Y', strtotime($item->created_at));?></td>
                <td ><?=date('H:i:s', strtotime($item->created_at));?></td>
                <td ><?=$item->created_by;?></td>
            </tr>
        <?php
                $i++;
                }
            endforeach;
        ?>
    </tbody>
</table>