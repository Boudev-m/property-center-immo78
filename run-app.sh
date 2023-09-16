# Execute this script to run the app
# Important : you need to execute docker desktop (the docker engine must be running)

# echo Creating Php-Apache image... Wait
docker build -t php-apache . -f ./docker/dockerfile.php-apache && \
# # echo Php-Apache image created successful.

# # echo Creating MySQL image... Wait
docker build -t mysql . -f ./docker/dockerfile.mysql && \
# # echo MySQL image created successful.

# # echo Creating containers... Wait
cd ./docker/ && docker-compose up -d && \
# echo Containers created successful.

echo "The app will start on port 8080... Wait" && \
sleep 20
echo "Go to http://localhost:8080/"
sleep 3
exit

### Open automatically the app in the web browser on the port 8080 ###

# If this script is running with PS
# echo "The app will start on port 8080... Wait"
# sleep 20
# start http://localhost:8080/

# If this script is running with bash
# it is necessary to install xdg-utils to run auto the app
# Check if xdg-open cmd is available
# if ! command -v xdg-open &> /dev/null; then
#     echo "xdg-utils isn't installed. Loading installation..." && \
#     # Vérifier la distribution Linux pour utiliser le gestionnaire de paquets approprié
#     sudo apt-get update && \
#     sudo apt-get install -y xdg-utils && \
#     echo "xdg-utils installed successful"
# else
#     echo "xdg-utils is already installed."
# fi && \
# echo "The app will start on port 8080... Wait"
# sleep 20
# xdg-open http://localhost:8080/