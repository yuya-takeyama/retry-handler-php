# A sample Guardfile
# More info at https://github.com/guard/guard#readme

guard 'phpunit', :tests_path => 'tests', :cli => '--colors --bootstrap=tests/bootstrap.php' do
  watch(%r{^src/(.*).php$}) {|m| "tests/#{m[1]}Test.php" }
  watch(%r{^tests/.+Test\.php$})
end
