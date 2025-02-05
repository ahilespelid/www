<?php include $this->layout('staff/base/head.php'); ?>

    <body>

        <?php include $this->layout('staff/menu.php'); ?>

        <div class="content">

            <?php include $this->layout('staff/header.php'); ?>

            <div class="body">
                <div class="reports-title">
                    <div class="reports-title__my-reports">
                        <div class="reports-title__my-reports__text">
                            <span class="my-reports__text__profile__name"><?= $user['firstname'] ?> <?= $user['secondname'] ?> <?= $user['lastname'] ?></span>
                        </div>
                    </div>
                </div>

                <div class="profile<?php if (!$user['active']) { echo ' fired'; } ?>">
                    <div class="fired-message"><i class="icon-user"></i>Данный сотрудник не активен</div>
                    <div class="profile__avatar-main">
                        <div class="profile__avatar">
                            <img class="avatar" src="<?= $user['avatar'] ?? $this->security->setEmptyAvatar() ?>" alt="">

                            <?php  $role = 2; ?>

                            <?php if($role === 1) { ?>
                                <label for="profile__input__avatar">
                                    <div class="profile__input__avatar__middle">
                                        <img src="../assets/img/svg/user_avatar.svg" alt="user_avatar">
                                        <span>Вы можете загрузить изображение в формате JPG, GIF или PNG.</span>
                                        <div class="choose__file">Выбрать файл</div>
                                    </div>
                                </label>
                                <input type="file" id="profile__input__avatar">
                            <?php } ?>
                        </div>
                        <div class="profile__footer">
                            <div class="profile__footer__ministry">
                                <?php if ($uin['type'] === 'district') { ?>
                                    <span class="ministry">Район:</span>
                                <?php } ?>
                                <span class="ministry__name"><?= $uin['owner'] ?></span>
                            </div>
                            <div class="profile__footer__address">
                                <i class="icon-pin"></i>
                                <span class="address__republic">Чеченская Республика,</span>
                                <span class="address__country">Российская Федерация</span>
                            </div>
                            <?php if ($user['social_in'] || $user['social_tg'] || $user['social_vk']) { ?>
                                <div class="profile__footer__social">
                                    <?php if ($user['social_in']) { ?>
                                        <a href="https://<?= $user['social_in'] ?>"><i class="icon-instagram"></i></a>
                                    <?php } ?>
                                    <?php if ($user['social_tg']) { ?>
                                        <a href="https://<?= $user['social_tg'] ?>"><i class="icon-telegram"></i></a>
                                    <?php } ?>
                                    <?php if ($user['social_vk']) { ?>
                                        <a href="https://<?= $user['social_vk'] ?>"><i class="icon-vk"></i></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($currentUser) { ?>
                                <div class="profile__footer__edit">
                                    <button class="button edit-profile-button">Редактировать профиль</button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="profile__form">

                        <div class="profile__form__name">
                            <div class="profile__form__first">ФИО</div>
                            <div class="profile__form__second">
                                <div class="profile__form__second__first-input">
                                    <span id="second_name">
                                        <div>Фамилия</div>
                                        <div><?= $user['lastname'] ?></div>
                                    </span>
                                </div>
                                <div class="profile__form__second__second-input">
                                    <span id="first_name">
                                        <div>Имя</div>
                                        <div><?= $user['firstname'] ?></div>
                                    </span>
                                </div>
                                <div class="profile__form__second__third-input">
                                    <span id="third_name">
                                        <div>Отчество</div>
                                        <div><?= $user['secondname'] ?></div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="profile__form__extra">
                            <div class="profile__form__first">Дополнительные данные</div>
                            <div class="profile__form__second">
                                <div class="profile__form__second__first-input">
                                    <span id="birthday">
                                        <div>Пол</div>
                                        <div><?= ($user['gender']) ? 'Мужской' : 'Женский' ?></div>
                                    </span>
                                </div>
                                <div class="profile__form__second__second-input">
                                    <span id="post">
                                        <div>Дата рождения</div>
                                        <div><?= (new DateTime($user['age']))->format('d.m.o') ?></div>
                                    </span>
                                </div>
                                <div class="profile__form__second__third-input">
                                    <span id="district">
                                        <div>Подразделение</div>
                                        <div><?= $uin['owner'] ?></div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="view-profile-form active">

                            <div class="profile__form__contacts">
                                <div class="profile__form__first">Контакты</div>
                                <div class="profile__form__second">
                                    <div class="profile__form__second__first-input">
                                        <span id="mail">
                                            <div>Почта</div>
                                            <div><?= $user['email'] ?></div>
                                        </span>
                                    </div>
                                    <div class="profile__form__second__second-input">
                                        <span id="telephone_number">
                                            <div>Телефон</div>
                                            <div><?= $user['phone'] ?></div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <?php if ($currentUser) { ?>
                            <form method="post" class="edit-profile-form">
                                <div class="profile__form__contacts">
                                    <div class="profile__form__first">Контакты</div>
                                    <div class="profile__form__second">
                                        <div class="profile__form__second__first-input">
                                            <span>
                                                <div>Почта</div>
                                                <div><input type="email" name="email" value="<?= $user['email'] ?>" id="mail" required></div>
                                            </span>
                                        </div>
                                        <div class="profile__form__second__second-input">
                                            <span>
                                                <div>Телефон</div>
                                                <div><input type="tel" name="phone" value="<?= $user['phone'] ?>" id="telephone_number" required></div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile__form__social">
                                    <div class="profile__form__first">Социальные сети</div>
                                    <div class="profile__form__second">
                                        <div class="profile__form__second__first-input">
                                            <span>
                                                <div>Телеграм</div>
                                                <div><input type="text" name="social_tg" value="<?= $user['social_tg'] ?>" id="telegram"></div>
                                            </span>
                                        </div>
                                        <div class="profile__form__second__first-input">
                                            <span>
                                                <div>Вконтакте</div>
                                                <div><input type="text" name="social_vk" value="<?= $user['social_vk'] ?>" id="vk"></div>
                                            </span>
                                        </div>
                                        <div class="profile__form__second__second-input">
                                            <span>
                                                <div>Инстаграм</div>
                                                <div><input type="text" name="social_in" value="<?= $user['social_in'] ?>" id="instagram"></div>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile__form__footer">
                                    <input type="submit" class="profile__form__footer__button" value="Сохранить изменения">
                                    <div class="profile__form__footer__text none">
                                        <img src="../assets/img/svg/check_ring_light.svg" alt="check_ring_light">
                                        Изменения сохранены
                                    </div>
                                </div>
                            </form>
                        <?php } ?>

                        <?php if ($this->security->userHasRole(['district_boss', 'ministry_boss']) && $this->user()['id_uin'] == $user['id_uin'] && $this->user()['id_role'] != $user['id_role']) { ?>

                            <div class="reports_main">
                                <div class="main__first">
                                    <div class="footer__info__time">
                                        <span>Деактивировать сотрудника</span>
                                    </div>
                                    <div>
                                        <div class="textarea">
                                            <label for="deleteUserCause">
                                                <textarea name="delete_user_cause" id="deleteUserCause" placeholder="Введите причину увольнения"></textarea>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="support__footer">
                                        <div class="support__footer__text"></div>
                                        <a href="/profile/fire?id=<?= $user['id'] ?>" onclick="return confirm('Вы уверены что хотите уволить данного сотрудника?')" class="button button-danger">Деактивировать сотрудника</a>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

        <script>
            if (document.querySelector('.edit-profile-button')) {
                let button = document.querySelector('.edit-profile-button');
                let editProfileForm = document.querySelector('.edit-profile-form');
                let viewProfileForm = document.querySelector('.view-profile-form');

                button.addEventListener('click', () => {
                    editProfileForm.classList.toggle('active');
                    viewProfileForm.classList.toggle('active');
                })
            }
        </script>

    </body>

<?php include $this->layout('staff/base/foot.php'); ?>
