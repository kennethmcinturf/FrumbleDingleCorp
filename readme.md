Thanks for giving the opportunity to continue in the interview process! This was a really fun project, and I am excited to show y'all  my solution

### Running the App
- Similar to the setup in the original project, going to clone the project from this repo
- cd into the repo in your local machine, and run `composer install`
- Be sure to set your `.env` with your local MySQl setup (can use .env.example as template)
- Run `php artisan migrate`
- I included a seeder file, to get you up and running with a good amount of data. Can be run with `php artisan db:seed --class=DatabaseSeeder`
- Run `npm run dev` to complile the Vue Components
- I just put a local server up for deployment. Run `php artisan serve`, and can run the app locally from `<http://127.0.0.1:8000>`

## Additional Features
Along with the base requirements of the app, I added a few additional features

### Data Edit
- Locations, Items, and Categories can all be edited, for all properties aside from id

### Pagination
- Set up server side pagination for the items table. Initial load will bring in the first 25 records. Just click `Load More Results` at the bottom of the table, and the next 25 items in the items table will be added

### Items Breakdown
- On the Report table, you can see a breakdown of the individual items greater than or equal to your price search for each grouping, as well as a breakdown by location and category of related items that match query parameters

### Sortation for Report Table
- Simple sort for each row in the price report

## Conclusion
Thanks again for taking the time to review this project, and to speak with me about That's Us and Thumbstopper. I look forward to any feedback from this project, and hope to speak with y'all again soon!