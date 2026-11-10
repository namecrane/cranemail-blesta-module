Blesta module for selling NameCrane Mail email domains from your NameCrane reseller plan.

Works with Blesta 6.

## Updating from 1.x

Upload the new `components/modules/namecrane_mail` folder over the old one, then open
**Settings > Company > Modules** and click **Upgrade** next to NameCrane Mail. Your servers,
packages and services carry over as they are. The 1.x module stops working on December 1,
2026.

## Setup

1. In the NameCrane client portal, create an API key under **My Profile > API Keys**. Give it
   Full Access, or limit it to your CraneMail reseller service with read and write.
2. Upload `components/modules/namecrane_mail` to your Blesta install.
3. Install **NameCrane Mail** under **Settings > Company > Modules > Available**.
4. Add a **Server**:
   - **Server Name**: any name, shown only to you.
   - **API Key**: your API key.
   - **Reseller Plan Service ID**: leave empty, unless the key covers more than one reseller
     plan. Then put the service ID of the plan this server is for (the number in its portal
     address).

   The key is checked when you save.
5. Create your packages with **NameCrane Mail** as the module and set their options.

The API reference is at https://namecrane.com/docs/api.

## What clients get

On the service's page, clients see their domain's status, the DNS records to set, usage, and
the administrator sign-in. Unless you hide them (the **Client Mailbox Management** option),
they can also manage:

- mailboxes: add, edit, change passwords, delete, and open their webmail;
- aliases (forwarding addresses);
- domain aliases.

The webmail address is your own login hostname when you've set one up under Login Portal in
the NameCrane portal, otherwise workspace.org.

Staff get an **Email Overview** tab on the service: status, usage, features, DNS records and
a button to open MXFilter.

## Package options

| Option | Description |
| ------ | ----------- |
| Disk Space (GB) | Storage for the domain |
| Max Email Users | Mailboxes (0 = unlimited) |
| Max User Aliases | Aliases (0 = unlimited) |
| Max Domain Aliases | Domain aliases (0 = unlimited) |
| Email Archiving (Years) | How long to archive mail |
| Email Archiving Direction | Incoming, outgoing, or both |
| MXFilter Spam Filtering | Filter the domain's mail through MXFilter |
| MXFilter Access | Which domain administrators can open MXFilter from webmail |
| File Storage | Online file storage |
| Office Suite | Online document editing |
| Webmail Login | Lets the client open any mailbox's webmail without its password |
| MXFilter Login | Lets the client and your staff open MXFilter without a password |
| Client Mailbox Management | Shows or hides the Mailboxes, Aliases and Domain Aliases tabs |

**Privacy (GDPR):** set **Webmail Login** and **MXFilter Login** to **Turned off** where only
the mailbox owner may open their mail. NameCrane then refuses those sign-ins for the domain
everywhere, including in your NameCrane portal.

Changing a package doesn't change its existing domains straight away (Blesta doesn't tell
modules about package edits). A service picks up its package's settings when it's saved with
**Use module** ticked or moved to another package.

## Order-time options (à la carte)

Configurable options override the package's settings. Give each option the name below (the
label can be anything). If you use them, set the matching package options to 0 to avoid
confusion.

| Option name | Type | Description |
| ----------- | ---- | ----------- |
| `disklimit` | quantity | Storage (GB) |
| `userlimit` | quantity | Mailboxes |
| `useraliaslimit` | quantity | Aliases |
| `domainaliaslimit` | quantity | Domain aliases |
| `spamexperts` | checkbox | MXFilter spam filtering |
| `archive_years` | dropdown | Archiving years: 0 (off), 1 to 10, 15 or 20 |
| `archive_direction` | dropdown | `in`, `out` or `inout` |
| `filestorage` | checkbox | Online file storage |
| `office` | checkbox | Online document editing |

## Email tags

Welcome emails can use `{service.namecrane_mail_domain}`, `{service.namecrane_mail_username}`
and `{service.namecrane_mail_password}` (the domain's administrator sign-in), and the package
options as `{package.disklimit}`, `{package.userlimit}` and so on.
