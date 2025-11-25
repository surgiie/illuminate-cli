# Contributing to illuminate-cli

Thank you for considering contributing to illuminate-cli! This document provides guidelines and instructions for contributing to the project.

## Code of Conduct

Please be respectful and considerate in all interactions. We're all here to improve the project together.

## Development Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Git

### Initial Setup

1. Fork the repository on GitHub
2. Clone your fork locally:
   ```bash
   git clone https://github.com/YOUR-USERNAME/illuminate-cli.git
   cd illuminate-cli
   ```

3. Install dependencies:
   ```bash
   composer install
   ```

4. Build the application:
   ```bash
   php illuminate app:build illuminate
   ```

## Development Workflow

### Running Tests

We use Pest for testing. Run the test suite with:

```bash
composer test
```

### Code Style

We use Laravel Pint for code formatting. Before committing:

```bash
# Check formatting
composer format:test

# Fix formatting issues
composer format
```

### Static Analysis

We use PHPStan with Larastan for static analysis:

```bash
# Run analysis
composer phpstan

# Generate baseline (if needed)
composer phpstan:baseline
```

### Making Changes

1. Create a new branch for your feature/fix:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes following these guidelines:
   - Write clear, descriptive commit messages
   - Add tests for new functionality
   - Update documentation as needed
   - Follow existing code style and patterns
   - Keep changes focused and atomic

3. Ensure all checks pass:
   ```bash
   composer test
   composer format:test
   composer phpstan
   ```

4. Commit your changes:
   ```bash
   git add .
   git commit -m "Add: description of your changes"
   ```

5. Push to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```

6. Create a Pull Request on GitHub

## Pull Request Guidelines

### Before Submitting

- [ ] All tests pass (`composer test`)
- [ ] Code is properly formatted (`composer format`)
- [ ] Static analysis passes (`composer phpstan`)
- [ ] Documentation is updated (if applicable)
- [ ] Commit messages are clear and descriptive

### PR Description

Please include:

- A clear description of the changes
- The motivation/reasoning behind the changes
- Any breaking changes or migration notes
- Related issue numbers (if applicable)

## Testing Guidelines

### Writing Tests

- Place feature tests in `tests/Feature/`
- Use descriptive test names that explain what is being tested
- Follow the existing test structure and patterns
- Test both success and failure cases
- Use Pest's expect syntax for assertions

Example:

```php
test('validation passes when valid data is provided', function () {
    $this->artisan('validation:make', [
        '--data' => json_encode(['email' => 'test@example.com']),
        '--rules' => json_encode(['email' => 'required|email']),
    ])->assertExitCode(0);
});
```

### Test Coverage

- Aim for comprehensive test coverage of new features
- Don't decrease existing coverage
- Focus on testing behavior, not implementation details

## Documentation

### Code Documentation

- Add PHPDoc blocks to all public methods and classes
- Include parameter types and return types
- Document any non-obvious logic with inline comments
- Keep docblocks concise and accurate

Example:

```php
/**
 * Validate the given data against rules.
 *
 * @param  array<string, mixed>  $data
 * @param  array<string, mixed>  $rules
 * @return \Illuminate\Validation\Validator
 */
protected function validator(array $data, array $rules): Validator
{
    // Implementation
}
```

### User Documentation

- Update relevant markdown files in `/docs` for user-facing changes
- Include examples where helpful
- Keep language clear and concise

## Adding New Components

When adding support for a new Laravel component:

1. Create command(s) in `app/Commands/[Component]/`
2. Add configuration in `config/` if needed
3. Create comprehensive tests in `tests/Feature/[Component]/`
4. Add documentation in `docs/[component].md`
5. Update the README.md to list the new component

## Reporting Issues

### Bug Reports

Include:
- Clear description of the bug
- Steps to reproduce
- Expected vs actual behavior
- PHP version and environment details
- Relevant code samples or error messages

### Feature Requests

Include:
- Clear description of the proposed feature
- Use cases and benefits
- Potential implementation approach (optional)
- Any potential drawbacks or considerations

## Questions?

If you have questions about contributing, feel free to:
- Open an issue for discussion
- Check existing issues and pull requests
- Review the documentation in `/docs`

## License

By contributing to illuminate-cli, you agree that your contributions will be licensed under the MIT License.

Thank you for contributing!
