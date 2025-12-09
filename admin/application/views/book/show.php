<?php $this->layout('layouts::main_template', ['title' => 'Books'])?>

<?php $this->start('css') ?>
<?php $this->stop() ?>

<?php $this->start('contents') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <img class="img-fluid" src="<?=$this->e(base_url('assets/img/books/'.$book['cover_img']))?>" alt="">
                    </div>
                    <aside class="col-12 col-lg-9">
                        <h3 class="mb-4"><?=$book['title']?></h3>
                        <dl class="row">
                            <dt class="col-2">
                                Penulis
                            </dt>
                            <dd class="col-10 mb-1">
                                <?=': '.$book['author']?>
                            </dd>
                            <dt class="col-2">
                                Penerbit
                            </dt>
                            <dd class="col-10 mb-1">
                                <?=': '.$book['publisher_name']?>
                            </dd>
                            <dt class="col-2">
                                Tahun Terbit
                            </dt>
                            <dd class="col-10 mb-1">
                                <?=': '.NULL?>
                            </dd>
                            <dt class="col-2">
                                ISBN
                            </dt>
                            <dd class="col-10 mb-1">
                                <?=': '.(!empty($book['isbn']) ? $book['isbn'] : '-' )?>
                            </dd>
                        </dl>
                        <p><?=$book['description']?></p>
                    </aside>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $this->stop() ?>

<?php $this->start('js') ?>
<?php $this->stop() ?>