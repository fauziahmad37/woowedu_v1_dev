<html>
    <head>
        <base href="<?=base_url()?>">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" media="all" type="text/css" href="<?=html_escape('assets/new/css/bootstrap.min.css')?>">
    </head>
    <body class="p-0 m-0" style="background-color: white">
        <header class="py-3" style="background-color: white">
            <h3 class="text-capitalize text-center">data area setting</h3>
        </header>
        <main class="py-3" style="background-color: white">
            <table class="table">
                <thead class="bg-success">
                    <tr>
                        <th class="text-light" scope="col">Area ID</th>
                        <th class="text-light" scope="col">Area Name</th>
                        <th class="text-light" scope="col">Parent ID</th>
                        <th class="text-light" scope="col">Parent Name</th>
                        <th class="text-light" scope="col">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($record as $d): ?>
                        <tr>
                            <td class="text-center"><?= html_escape($d['area_id'])?></td>
                            <td class="text-center"><?= html_escape($d['area_name'])?></td>
                            <td class="text-center"><?= html_escape($d['parent_id'])?></td>
                            <td class="text-center"><?= html_escape($d['parent_name'])?></td>
                            <td class="text-center"><?= html_escape($d['remarks'])?></td>
                        </tr>
                    <?php 
                        endforeach; 
                        unset($data);
                    ?>
                </tbody>
            </table>
        </main>
    </body>
</html>