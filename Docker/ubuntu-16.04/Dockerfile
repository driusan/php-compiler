FROM ircmaxell/php-compiler:16.04-dev

COPY ./ /compiler

WORKDIR /compiler

RUN mv /composer.phar /usr/local/bin/composer

RUN composer install --no-ansi --no-interaction --no-progress --no-scripts --optimize-autoloader

RUN patch -p0 -d vendor/pre/plugin/hidden/vendor/yay/yay/src/ < Docker/yaypatch.patch


ENTRYPOINT ["php", "/compiler/bin/jit.php"]

CMD ["-r", "echo \"Hello World\n\";"]
