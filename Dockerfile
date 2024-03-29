# a PHP image bundle with nginx
FROM webdevops/php-nginx:7.3

ARG PSR_VERSION=0.7.0
ARG PHALCON_VERSION=3.4.5
ARG PHALCON_EXT_PATH=php7/64bits

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=compose

RUN set -xe && \
    # Download PSR, see https://github.com/jbboehr/php-psr
    curl -LO https://github.com/jbboehr/php-psr/archive/v${PSR_VERSION}.tar.gz && \
    tar xzf ${PWD}/v${PSR_VERSION}.tar.gz && \
    # Download Phalcon
    curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz && \
    tar xzf ${PWD}/v${PHALCON_VERSION}.tar.gz && \
    docker-php-ext-install -j $(getconf _NPROCESSORS_ONLN) \
        ${PWD}/php-psr-${PSR_VERSION} \
        ${PWD}/cphalcon-${PHALCON_VERSION}/build/${PHALCON_EXT_PATH} \
    && \
    # Remove all temp files
    rm -r \
        ${PWD}/v${PSR_VERSION}.tar.gz \
        ${PWD}/php-psr-${PSR_VERSION} \
        ${PWD}/v${PHALCON_VERSION}.tar.gz \
        ${PWD}/cphalcon-${PHALCON_VERSION} \
    && \
    php -m
    
RUN apt-get update && apt-get install -y \
  ca-certificates \
  curl


ENV WEB_DOCUMENT_ROOT=/var/www/html/demo/public
