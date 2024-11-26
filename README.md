Blesta module to handle provisioning of domains on the Namecrane Mail platform.

*This is still in Beta, feedback is welcome!*

***Blesta Tutorial***

1) Login to NameCrane and view the CraneMail management Portal.
2) Pull down the <code>Reseller Tools</code> menu option and pick <code>API Key</code>.
3) Click the `Refresh` icon incase you don't already have an API key.
4) Upload the module to your Blesta install.
5) Add a `Server`, picking `Namecrane Mail` from the module list.
6) Place the `API Key` from the <code>Reseller Tools</code> section into the `API Key` text box.
8) Create your plans, specifying `Namecrane Mail` as the module.

***Missing Features***

- Allow the client to change thier SpamExperts administrator policy?
- Pulling resellers whitelabel domain and use that in place of our included whitelabel domain

***Order time customizing (À la carte)***

You can to add `Configuration Options` to allow users to on-demand customize their resources. These values override whatever is set in the plan itself.

***If you're using this feature, we recommend setting the limits to **0** inside of your packages to stop any confusion.***

Available values:

| Name | Label | Type | Description |
| ------------| ------- | ---- | ----------- |
| `userlimit` | `User Emails` | `quantity` | How many email accounts the domain may have |
| `disklimit` | `Disk Storage (GB)` | `quantity` | Total disk storage available (GB) |
| `useraliaslimit` | `User Aliases` | `quantity` | How many email aliases the domain may have | 
| `spamexperts` | `Spamexperts` | `checkbox` | Enable SpamExperts anti spam filtering on the domain | 
| `domainaliaslimit` | `Domain Aliases` | `quantity` | How many domain aliases the domain may have |
| `archive_years` | `Email Archiving (years)` | `dropdown` | How many years to archive emails for |
| `archive_direction` | `Email Archiving Direction` | `dropdown` (values `in`, `out,` `inout`) | Archive incoming, outgoing, or both, emails |


***Archiving Years Options***

We recommend using a dropdown for this as there is gaps in the accepted values.

| Value | Description |
|-|-|
| `1` | `1 Year` |
| `2` | `2 Years` |
| `3` | `3 Years` |
| `4` | `4 Years` |
| `5` | `5 Years` |
| `6` | `6 Years` |
| `7` | `7 Years` |
| `8` | `8 Years` |
| `9` | `9 Years` |
| `10` | `10 Years` |
| `15` | `15 Years` |
| `20` | `20 Years` |

