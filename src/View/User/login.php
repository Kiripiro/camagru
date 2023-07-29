<section class="login">
    <div class="hero is-fullheight is-light">
        <div class="hero-body" style="height: 80vh; overflow: hidden;">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <div class="box">
                        <div class="box">
                            <img src="/Media/logo/logo.png" alt="Camagru">
                        </div>
                        <hr class="login-hr">
                        <h3 class="title is-4 has-text-black">Sign in</h3>
                        <hr class="login-hr">
                        <div class="box">
                            <div class="title has-text-grey is-5">Please enter your username and password.
                            </div>
                            <form class="form" action="login" method="post">
                                <div class="field">
                                    <div class="control">
                                        <input class="input is-normal" type="text" name="username"
                                            placeholder="Username" autofocus="">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="control">
                                        <div class="columns is-mobile">
                                            <div class="column is-10">
                                                <input class="input is-normal" type="password" name="password"
                                                    placeholder="Mot de passe">
                                            </div>
                                            <div class="column is-1 has-text-centered">
                                                <span class="icon is-small is-right toggle-password mt-3">
                                                    <i class="fa-solid fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="button is-block is-info is-normal is-fullwidth">Sign in</button>
                            </form>
                            <hr class="login-hr">
                            <div class="title has-text-link is-7">
                                <a class="has-text-blue" href="/forgot-password">Forgot your password ?</a>
                            </div>
                            <hr class="login-hr">
                            <div class="title has-text-grey is-6">
                                Don't have an account ?
                                <a class="has-text-link" href="/register">Sign up here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>