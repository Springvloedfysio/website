# buildenv = ENV.key?('BUILD_ENV') ? ENV['BUILD_ENV'] : 'development'

watch('src/Makefile')                   { |md| system "make build"               ; puts "\n"      }
watch('src/less/.*\.less')              { |md| system "make build_css"           ; puts "\n"      }
watch('src/js/plugins.*\.js')           { |md| system "make build_plugins_js"    ; puts "\n"      }
watch('src/js/boot\.js')                { |md| system "make build_boot_js"       ; puts "\n"      }
watch('src/js/libs/.*\.js')             { |md| system "make build_libs_js"       ; puts "\n"      }
watch('src/js/libs/bootstrap/.*\.js')   { |md| system "make build_libs_js"       ; puts "\n"      }