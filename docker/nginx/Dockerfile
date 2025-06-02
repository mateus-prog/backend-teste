FROM nginx:1.21.3

#Deletando logs antigos
RUN rm -f /var/log/nginx/access.log && rm -f /var/log/nginx/error.log

#Copiando arquivo para volume
RUN rm /etc/nginx/conf.d/default.conf
COPY laravel.conf /etc/nginx/conf.d/laravel.conf

COPY entrypoint.sh /entrypoint.sh

RUN chmod +x entrypoint.sh
ENTRYPOINT [ "/entrypoint.sh" ]