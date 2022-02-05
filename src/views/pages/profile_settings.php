<?=$render('header', ['loggedUser' => $loggedUser]);?>

<section class="container main">
        <?=$render('sidebar', ['activeMenu' => 'search']);?>

                <section class="feed mt-10">

                    <div class="row">
                        <div class="column pr-5">
                            <form method="POST" action="<?=$base;?>/config">
                                <h1>Configurações</h1>
                                <div class="upload"> 
                                    <br/>
                                    <br/>
                                    <p>Novo Avatar:</p>
                                    <br/>
                                    <input type="file" />
                                </div>
                                <div class="upload"> 
                                    <br/>
                                    <br/>
                                    <p>Nova Capa:</p>
                                    <br/>
                                    <input type="file" />
                                </div>

                                <br/>
                                <br/>
                                <hr/>
                                <br/>
                                <div class="form-config">
                                    <label>
                                        Nome Completo: <br/>
                                        <input type="text" name="name" />
                                    </label>
                                    <br/>
                                    <label>
                                        Data de nascimento: <br/>
                                        <input type="text" name="birthdate" />
                                    </label>
                                    <br/>
                                    <label>
                                        E-mail: <br/>
                                        <input type="email" name="email"  />
                                    </label>
                                    <br/>
                                    <label>
                                        Cidade: <br/>
                                        <input type="text" name="birthdate" />
                                    </label>
                                    <br/>
                                    <label>
                                        Trabalho: <br/>
                                        <input type="text" name="birthdate" />
                                    </label>
                                    <hr/>
                                    <br/>
                                    <label>
                                        Nova Senha: <br/>
                                        <input type="text" name="birthdate" />
                                    </label>
                                    <br/>
                                    <label>
                                        Confirmar Nova Senha: <br/>
                                        <input type="text" name="birthdate" />
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