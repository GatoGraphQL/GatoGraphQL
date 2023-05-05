# Why are there so many packages in the repo

This is to have GraphQL by PoP be CMS-agnostic, because it is impossible to make a GraphQL server that works everywhere, using 100% the same PHP code.

Splitting the code into granular packages enables to inject reuse most of the code for all frameworks and CMSs, and reimplement only what is truly specific to each of them.

For this to work, every functionality must be split into 2 packages:

- a CMS-agnostic package, containing all business logic, using only "vanilla" PHP code. This package will include the contracts to be satisfied by the CMS-specific package
- a CMS-specific package, satisfying the contracts for that CMS

After splitting all the functionality from the repo into standalone components, it ended up producing around 200 packages.

Minimizing the amount of code that must be re-implemented, and avoiding duplicate code across packages, are the main drivers defining how the code is split into packages.

## Additional resources

- [💁🏽‍♂️ Why to support CMS-agnosticism, the Gato GraphQL was split to ~90 packages, and benefits and drawbacks of this approach](https://gato-graphql.com/blog/why-to-support-cms-agnosticism-the-gato-graphql-split-to-around-90-packages/)
- [Abstracting WordPress Code To Reuse With Other CMSs: Concepts (Part 1)](https://www.smashingmagazine.com/2019/11/abstracting-wordpress-code-cms-concepts/)
- [Abstracting WordPress Code To Reuse With Other CMSs: Implementation (Part 2)](https://www.smashingmagazine.com/2019/11/abstracting-wordpress-code-reuse-with-other-cms-implementation/)
