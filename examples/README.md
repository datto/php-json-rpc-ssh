# Prequisites

This example shows how you might create an SSH API, where clients communicate
with a remote API server over SSH.

In order to run this example, you'll need a working SSH environment.

If you're using Linux in your development environment, you can set up a
development SSH environment like this:

1. Install the "openssh-server" package in your desktop environment
2. Create an SSH key for your desktop user (if you don't already have one):
`ssh-keygen -t rsa`
3. Allow your desktop user to SSH within your local desktop environment:
`ssh-copy-id $user@localhost`

In a production environment, the remote server is responsible for ensuring
that an inbound SSH request is safe to accept. For example, the remote server
could allow remote SSH users to invoke only the API endpoint script.
