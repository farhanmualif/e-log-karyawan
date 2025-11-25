# KONFIGURASI di file /xampp/apache/conf/extra/httpd-vhost.conf

# didalam tag

<VirtualHost>
    Alias /e-log-karyawan "C:/xampp/htdocs/e-log-karyawan/public"
    <Directory "C:/xampp/htdocs/e-log-karyawan/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
