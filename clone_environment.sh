#!/bin/bash
clear 
set -e  # Exit immediately if a command exits with a non-zero status

# Move to the directory above where this script is located
script_dir="$(dirname "$(realpath "$0")")"
cd "$script_dir/.."

# Get the list of directories matching storj_* in the current directory
options=(storj_*)

# Ensure there are valid directories to choose from
if [ ${#options[@]} -eq 0 ]; then
    echo "No valid storj_* directories found!"
    exit 1
fi

# Display options to the user
echo "Select a directory to copy:"
select src_dir in "${options[@]}"; do
    if [ -n "$src_dir" ]; then
        break
    else
        echo "Invalid selection. Try again."
    fi
done

# Extract the numeric part
old_number=$(echo "$src_dir" | grep -oP '\d{1,5}$')

if [[ ! "$old_number" =~ ^[0-9]{1,5}$ ]]; then
    echo "Error: Extracted number is invalid!"
    exit 1
fi

# Prompt user for the new number
while true; do
    read -p "Enter the new number (max 5 digits): " new_number
    if [[ "$new_number" =~ ^[0-9]{1,5}$ ]]; then
        break
    else
        echo "Invalid input. Please enter a number (max 5 digits)."
    fi
done

# Define the new directory name
new_dir="storj_$new_number"

# Ensure the new directory does not already exist
if [ -d "$new_dir" ]; then
    echo "Error: Directory $new_dir already exists!"
    exit 1
fi

# Copy the directory
cp -r "$src_dir" "$new_dir"

# Navigate to the new directory
cd "$new_dir"

# Run update_files.sh
/bin/bash update_files.sh

# Inform the user about the next input
echo "Now enter your dashboard IP and dashboard port number (typically 14002)."

# Run file_setup.sh
/bin/bash file_setup.sh

# Modify docker-compose.yml to replace old number with new number
if [ -f docker-compose.yml ]; then
    sed -i "s/$old_number/$new_number/g" docker-compose.yml
    echo "Updated docker-compose.yml with new number: $new_number"
else
    echo "Error: docker-compose.yml not found!"
    cd ..
    rm -rf "$new_dir"
    exit 1
fi

# Completion message
echo "Directory $new_dir has been successfully created and updated."
