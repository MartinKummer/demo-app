FROM php:8.2-apache

RUN apt-get update

# Install node
COPY --from=node:18 /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node:18 /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN npm install yarn -g
RUN npm install -g corepack
RUN corepack enable
RUN yarn set version stable
RUN yarn config set --home enableTelemetry 0

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Set apache
RUN a2enmod proxy
RUN a2enmod proxy_http
RUN a2enmod rewrite

# Install git
RUN apt-get install -y openssh-client git
RUN mkdir -p /root/.ssh && ln -s /run/secrets/ssh_key /root/.ssh/id_rsa
RUN chown -R root:root /root/.ssh

# Install php extensions
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN pecl install xdebug && mkdir /var/log/xdebug && docker-php-ext-enable xdebug
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip
RUN install-php-extensions gd pdo_mysql bcmath intl opcache apcu
