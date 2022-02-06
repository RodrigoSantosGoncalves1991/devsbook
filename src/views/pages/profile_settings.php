<?=$render('header', ['loggedUser' => $loggedUser]);?>

<section class="container main">
        <?=$render('sidebar', ['activeMenu' => 'config']);?>

                <section class="feed mt-10">
                    <?php if(!empty($flash)): ?>
                        <div class="flash"><?php echo $flash;?></div>  
                    <?php endif; ?>

                    <div class="row">
                        <div class="column pr-5">
                            <form method="POST" enctype="multipart/form-data" action="<?=$base;?>/config">
                                <h1>Configurações</h1>
                                <div class="upload"> 
                                    <br/>
                                    <br/>
                                    <p>Novo Avatar:</p>
                                    <br/>
                                    <input type="file" name="avatar" />
                                    <br/>
                                    <br/>
                                    <img src="<?=$base;?>/media/avatars/<?=$loggedUser->avatar;?>" />
                                </div>
                                <div class="upload"> 
                                    <br/>
                                    <br/>
                                    <p>Nova Capa:</p>
                                    <br/>
                                    <input type="file" name="cover" />
                                    <br/>
                                    <br/>
                                    <img src="<?=$base;?>/media/covers/<?=$loggedUser->cover;?>" />
                                </div>

                                <br/>
                                <br/>
                                <hr/>
                                <br/>
                                <div class="form-config">
                                    <label>
                                        Nome Completo: <br/>
                                        <input type="text" name="name" value="<?=$loggedUser->name;?>" />
                                    </label>
                                    <br/>
                                    <label>
                                        Data de nascimento: <br/>
                                        <input type="text" name="birthdate" value="<?=date('d/m/Y', strtotime($loggedUser->birthdate));?>" id="birthdate" />
                                    </label>
                                    <br/>
                                    <label>
                                        E-mail: <br/>
                                        <input type="email" name="email" value="<?=$loggedUser->email;?>"  />
                                    </label>
                                    <br/>
                                    <label>
                                        Cidade: <br/>
                                        <input type="text" name="city" value="<?=$loggedUser->city;?>"  />
                                    </label>
                                    <br/>
                                    <label>
                                        Trabalho: <br/>
                                        <input type="text" name="work" value="<?=$loggedUser->work;?>" />
                                    </label>
                                    <hr/>
                                    <br/>
                                    <label>
                                        Nova Senha: <br/>
                                        <input type="password" name="new_password" />
                                    </label>
                                    <br/>
                                    <label>
                                        Confirmar Nova Senha: <br/>
                                        <input type="password" name="new_password_confirm" />
                                    </label>
                                    <br/>
                                    <input class="button" type="submit" value="Salvar" />
                                </div>
                            </form >
                        </div>
                    </div>
                    
                </section>

</section>

<?=$render('footer');?>

<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'), {
            mask: '00/00/0000'
        }
    );
</script>