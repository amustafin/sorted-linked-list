FROM php:8.1-fpm-alpine

ARG UNAME=docker-user
ARG UGROUP=docker-user
ARG UID=1000
ARG GID=1000

RUN mkdir -p /var/run/sll/default/
RUN mkdir -p /var/log/sll/default/

#RUN addgroup -S appgroup && adduser -S appuser -G appgroup
RUN adduser -S --uid $UID $UNAME
RUN addgroup -S --gid $GID $UGROUP

RUN chown -R $UNAME:$UGROUP /var/run/sll/default/
RUN chown -R $UNAME:$UGROUP /var/log/sll/default/

RUN apk add bash
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer