# Use the official Windows Server Core with IIS image
FROM mcr.microsoft.com/windows/servercore/iis

# Install and enable required features
RUN powershell -Command \
    Install-WindowsFeature Web-Server; \
    Install-WindowsFeature NET-Framework-45-ASPNET; \
    Install-WindowsFeature Web-Asp-Net45

# Set TLS 1.2
RUN powershell -Command \
    [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12


# Configure IIS to handle PHP files
RUN echo "<?php echo phpinfo(); ?>" > C:\inetpub\wwwroot\index.php

# Download and install MySQL client for Windows
ADD https://dev.mysql.com/get/Downloads/MySQLInstaller/mysql-installer-web-community-8.0.28.0.msi C:/web/mysql-installer.msi
RUN powershell -Command \
    Start-Process msiexec.exe -ArgumentList '/i', 'C:\web\mysql-installer.msi', '/quiet', '/qn', '/norestart' -Wait ; \
    Remove-Item 'C:\web\mysql-installer.msi' -Force

# Open port 80
EXPOSE 80

# Command to start IIS
CMD ["C:\\ServiceMonitor.exe", "w3svc"]

