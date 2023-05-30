# Content orchestration

Use "Service Orchestration" as the foundation for the concept

"Automation vs Orchestration":
  > Automation refers to automating a single process or a small number of related tasks (e.g., deploying an app). Orchestration refers to managing multiple automated tasks to create a dynamic workflow (e.g., deploying an app, connecting it to a network, and integrating it with other systems).

  Whereas automation is a simple "if this, then that" process, orchestration has many moving parts and requires advanced logic that can:

    Make decisions based on an output from an automated task.
    Branch out into different steps and actions.
    Adapt to changing circumstances and conditions.
    Coordinate multiple tasks at the same time.
From:
  https://phoenixnap.com/blog/orchestration-vs-automation

Idea:
  when product is published in WooCommerce, publish it in Amazon
  when a unit of the product is sold in WooCommerce, decrease the stock in Amazon
    if the stock in WooCommerce reached zero, then disable the product page:
      in WooCommerce
      in Amazon
        If anyone was buying the product at that moment, send a discount coupon for another product
  when a unit of the product is sold in Amazon, decrease the stock in WooCommerce
    if the stock in WooCommerce reached zero, then disable the product page:
      in WooCommerce
      in Amazon
        If anyone was buying the product at that moment, send a discount coupon for another product
