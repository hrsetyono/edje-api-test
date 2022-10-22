# Edje API Test

This is the sample API used by [WP Vue Boilerplate](https://github.com/hrsetyono/wp-vue-boilerplate)

Consisted of three main folders:

- `/admin` - for codes that run in Admin only.
- `/public` - for codes that run in Public only.
- `/includes` - for codes that run in both Admin and Public.

## How to Use

1. Copy this repo to `wp-content/plugins` folder.

1. Do a case-sensitive **search & replace** for these 3 words:

    - `H_API`
    - `h_api`
    - `h-api`

1. Start working on the plugin.

## How to use Webpack

1. Install [Node JS](https://nodejs.org/en/download/). Pick the LTS version. At the time of writing, we are using v4.17.4.

1. Open command-line in this repo and run this command to install Node packages:

    ```
    npm install
    ```

    **Note**: If you are using VS Code, type `` [CTRL + `] `` to open command-line.

1. To auto-compile whenever there are changes in JS or CSS, run:

   ```
   npm run dev
   ```

1. To minify the JS and CSS before you deploy the plugin, run:

    ```
    npm run build
    ```
