FROM php:alpine

LABEL Description="" Vendor="Elliot J. Reed" Version="1.0"

ENV PATH="/app/bin:${PATH}"

WORKDIR /app

COPY ./bin/check.php /app/bin/check
COPY ./src /app/src
COPY ./composer.json /app/composer.json
COPY ./composer.lock /app/composer.lock

RUN { find /usr/local/lib -type f -print0 | xargs -0r strip --strip-all -p 2>/dev/null || true; } && \
    rm -rf /tmp/* /usr/local/lib/php/doc/* /var/cache/apk/* && \
    cd /app && \
    curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/app --filename=composer && \
    chmod +x /app/composer /app/bin/check && \
    /app/composer install --no-progress --no-interaction --classmap-authoritative --no-suggest --no-dev && \
    rm -f /app/composer /app/composer.json /app/composer.lock && \
    cd /app/vendor && \
    find . -type f \( -iname "*readme*" ! -iname "*.php" \) -exec rm -vf {} + && \
    find . -type f \( -iname "*changelog*" ! -iname "*.php" \) -exec rm -vf {} + && \
    find . -type f \( -iname "*license*" ! -iname "*.php" \) -exec rm -vf {} +

VOLUME ["/app"]
ENTRYPOINT ["/app/bin/check"]
CMD ["--help"]
