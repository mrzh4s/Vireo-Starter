# Contributing to Vireo Starter

Thank you for considering contributing to Vireo Starter! This document outlines the guidelines for contributing to this project.

## Code of Conduct

Please be respectful and considerate in all interactions. We are committed to providing a welcoming and inclusive environment for everyone.

## How to Contribute

### Reporting Bugs

If you find a bug, please open an issue on GitHub with:

- A clear, descriptive title
- Steps to reproduce the issue
- Expected behavior
- Actual behavior
- PHP version, Node.js version, and operating system
- Any relevant error messages or logs

### Suggesting Features

Feature requests are welcome! Please open an issue with:

- A clear description of the feature
- The problem it solves or use case it addresses
- Any implementation ideas you have

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```
3. **Make your changes** following the coding standards below
4. **Write or update tests** for your changes
5. **Run the test suite** to ensure nothing is broken:
   ```bash
   composer test
   ```
6. **Commit your changes** with a clear commit message
7. **Push to your fork** and submit a pull request

## Coding Standards

### PHP

- Follow PSR-12 coding standards
- Use type declarations for parameters and return types
- Use meaningful variable and method names
- Keep methods focused and concise
- Add PHPDoc blocks for public methods

### JavaScript/React

- Use functional components with hooks
- Follow the existing code style
- Use meaningful component and variable names
- Keep components focused and reusable

### Vertical Slice Architecture

When adding new features:

1. Create a new directory under `Features/`
2. Organize by use case (Commands, Queries, Handlers)
3. Keep shared code in `Shared/` subdirectory
4. Use ports and adapters pattern for external dependencies

Example structure:
```
Features/
└── YourFeature/
    ├── CreateSomething/
    │   ├── CreateSomethingCommand.php
    │   └── CreateSomethingHandler.php
    ├── GetSomething/
    │   ├── GetSomethingQuery.php
    │   └── GetSomethingHandler.php
    └── Shared/
        ├── Ports/
        │   └── SomethingRepositoryInterface.php
        └── Adapters/
            └── PgSomethingRepository.php
```

## Testing

- Write unit tests for new functionality
- Place tests in `Tests/Unit/` mirroring the source structure
- Use descriptive test method names
- Test both success and failure cases

## Commit Messages

- Use clear, descriptive commit messages
- Start with a verb in present tense (Add, Fix, Update, Remove)
- Reference issue numbers when applicable

Examples:
- `Add user profile feature`
- `Fix validation error on login form`
- `Update rate limiting configuration`

## Questions?

If you have questions, feel free to open an issue for discussion.

Thank you for contributing!
