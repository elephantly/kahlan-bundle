## Command options

```shell
Usage:
  php app/console kahlan:run [options]

Options:
      --config=CONFIG             The PHP configuration file to use
                                        (default: 'kahlan-config.php').
      --src=SRC                   Paths of source directories
                                        (default: ['src']).
      --spec=SPEC                 Paths of specification directories
                                        (default: ['spec']).
      --pattern=PATTERN           A shell wildcard pattern
                                        (default: '*Spec.php').

  -r, --reporter=REPORTER         The name of the text reporter to use, the built-in text
                                  reporters are 'dot', 'bar', 'json', 'tap' & 'verbose'
                                  (default: 'dot'). You can optionally redirect the reporter
                                  output to a file by using the colon syntax (multiple
                                  --reporter options are also supported).

  -c, --coverage=COVERAGE         Generate code coverage report. The value specify the level
                                  of detail for the code coverage report (0-4). If a
                                  namespace, class, or method definition is provided, it will
                                  generate a detailed code coverage of this specific scope
                                        (default `''`).
      --clover=CLOVER             Export code coverage report into a Clover XML format.
      --istanbul=ISTANBUL         Export code coverage report into an istanbul compatible JSON
                                  format.
      --lcov=LCOV                 Export code coverage report into a lcov compatible text
                                  format.

      --ff=FF                     Fast fail option. `0` mean unlimited (default: `0`).
      --no-colors=NO-COLORS       To turn off colors.
                                        (default: `false`).
      --no-header=NO-HEADER       To turn off header.
                                        (default: `false`).
      --include=INCLUDE           Paths to include for patching.
                                        (default: `['*']`).
      --exclude=EXCLUDE           Paths to exclude from patching.
                                        (default: `[]`).
      --persistent=PERSISTENT     Cache patched files
                                        (default: `true`).
      --autoclear=AUTOCLEAR       Classes to autoclear after each spec
                                        (default: [
                                          `'Kahlan\Plugin\Monkey'`,
                                          `'Kahlan\Plugin\Call'`,
                                          `'Kahlan\Plugin\Stub'`,
                                          `'Kahlan\Plugin\Quit'`
                                        ])

  -h, --help                      Display this help message
  -q, --quiet                     Do not output any message
  -V, --version                   Display this application version
      --ansi                      Force ANSI output
      --no-ansi                   Disable ANSI output
  -n, --no-interaction            Do not ask any interactive question
  -s, --shell                     Launch the shell.
      --process-isolation         Launch commands from shell as a separate process.
  -e, --env=ENV                   The Environment name.
                                        (default: "dev")
      --no-debug                  Switches off debug mode.

  -cc, --cache-clear=CACHE-CLEAR  Cache patched files
                                        (default: `true`).

  -v|vv|vvv, --verbose            Increase the verbosity of messages: 1 for normal output, 2
                                  for more verbose output and 3 for debug
  ```
