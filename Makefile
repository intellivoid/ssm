clean:
	rm -rf build

update:
	ppm --generate-package="src/ssm"

build:
	mkdir build
	ppm --no-intro --compile="src/ssm" --directory="build"

install:
	ppm --no-intro --no-prompt --fix-conflict --install="build/net.intellivoid.ssm.ppm"