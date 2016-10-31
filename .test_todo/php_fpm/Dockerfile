FROM php:7.0-fpm

MAINTAINER Eugene Sawfly aka Yevgeniy Trach <eugene.sawfly@gmail.com>

RUN apt-get update
RUN docker-php-ext-install pdo pdo_mysql
EXPOSE 9000
CMD ["php-fpm"]
