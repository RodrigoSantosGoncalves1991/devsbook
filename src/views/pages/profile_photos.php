<?=$render('header', ['loggedUser' => $loggedUser]);?>

<section class="container main">
        <?=$render('sidebar', ['activeMenu' => 'photos']);?>

                <section class="feed">

                    <?=$render('perfil-header', [
                                    'loggedUser' => $loggedUser,
                                    'user' => $user,
                                    'isFollowing' => $isFollowing
                    ]);?>
                
                    <div class="row">
                        
                        <div class="column">
                    
                        <div class="box">
                            <div class="box-body">
                                <div class="full-user-photos">
                                    <?php $photos_length = count($user->photos); ?>
                                    <?php if($photos_length === 0): ?>
                                        Nenhuma foto
                                    <?php endif; ?>
                                    <?php for($i = 0; $i < $photos_length; $i++): ?>
                                        <div class="user-photo-item">
                                            <a href="#modal-<?=$user->photos[$i]->id;?>" rel="modal:open">
                                                <img src="<?=$base;?>/media/uploads/<?=$user->photos[$i]->body;?>" />
                                            </a>
                                            <div id="modal-<?=$user->photos[$i]->id;?>" style="display:none">
                                                <img src="<?=$base;?>/media/uploads/<?=$user->photos[$i]->body;?>" />
                                            </div>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>

                </section>

</section>

<?=$render('footer');?>