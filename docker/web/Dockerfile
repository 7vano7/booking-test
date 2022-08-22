FROM ubuntu:22.04

LABEL maintainer="vano.mig@gmail.com"
LABEL version="1.0"
LABEL description="WS"

ENV TZ=UTC
ARG DEBIAN_FRONTEND=noninteractive

#update system and install sofware
RUN apt update && apt -y upgrade
RUN apt install -y git lsb-release ca-certificates apt-transport-https software-properties-common
RUN update-ca-certificates
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt install -y gcc g++ make autoconf dpkg-dev dpkg pkgconf tzdata file re2c xdg-utils net-tools curl wget nano
RUN apt install -y openssl openssh-client mysql-client gettext bzip2 zip tar llvm libtool
RUN apt install -y libonig-dev libpq-dev libzip-dev zlib1g-dev

FROM php:8.1-fpm
RUN docker-php-ext-install mysqli pdo pdo_mysql sockets
