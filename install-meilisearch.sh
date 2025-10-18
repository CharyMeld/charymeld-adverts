#!/bin/bash

# Install Meilisearch
echo "Installing Meilisearch..."

# Download Meilisearch
curl -L https://install.meilisearch.com | sh

# Move to /usr/local/bin (optional)
sudo mv ./meilisearch /usr/local/bin/

# Make it executable
sudo chmod +x /usr/local/bin/meilisearch

echo "Meilisearch installed successfully!"
echo "To run Meilisearch, execute: meilisearch"
echo "Or run in the background: nohup meilisearch &"
