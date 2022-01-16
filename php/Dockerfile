FROM php:7.4-fpm

ARG workdir
ARG uid
ARG user

RUN apt update \
  && apt install -y \
      git \
      curl \
      libpng-dev \
      libonig-dev \
      libxml2-dev \
      libmcrypt-dev \
      zip \
      unzip \
  && docker-php-ext-install \
      pdo \
      pdo_mysql \
      mbstring \
      exif \
      bcmath

RUN pecl install mcrypt-1.0.4 \
    && docker-php-ext-enable mcrypt;

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN useradd -G www-data,root -u $uid -ms /bin/bash -d /home/$user $user

USER $user
WORKDIR $workdir
CMD ["php-fpm"]
