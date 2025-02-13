## 📥 Installation Guide  

Follow these steps to download, extract, and run the StorjDashboard Docker control panel. These commands are compatible with most Linux distributions.  

### **Step 1: Install Required Packages**  
Ensure you have `curl` and `unzip` installed. Run the following command based on your Linux distribution:  

**Debian/Ubuntu:**  
```bash
sudo apt update && sudo apt install -y curl unzip
```

**CentOS/RHEL:**  
```bash
sudo yum install -y curl unzip
```

**Arch Linux:**  
```bash
sudo pacman -Sy curl unzip
```

### **Step 2: Download the Latest Release**  
Go to the [StorjDashboard Docker Releases](https://github.com/storjdashboard/storjdashboard_docker/releases/latest) page and copy the download link for the `.zip` file. Then, run:  

```bash
curl -L -o storjdashboard_docker.zip "https://github.com/storjdashboard/storjdashboard_docker/archive/refs/tags/v1.0.0.zip"
```

### **Step 3: Extract the Files**  
```bash
unzip storjdashboard_docker.zip
cd storjdashboard_docker
```

### **Step 4: Run the Control Panel**  
```bash
bash controlpanel.sh
```

### **Important Notes:**  
- The control panel runs on **port 80**, so ensure no other services (like Apache or Nginx) are using this port.  
- Do **not** expose this panel to the internet for security reasons.  
- After setting up your servers, you can start them by running:  
  ```bash
  bash servers.sh
  ```

For further details, visit our [GitHub Repository](https://github.com/storjdashboard/storjdashboard_docker).

