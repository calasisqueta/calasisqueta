# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.synced_folder ".", "/app", owner: 'www-data'

  config.vm.define 'calasisqueta' do |calasisqueta|
    calasisqueta.vm.hostname = 'calasisqueta.local'
    calasisqueta.vm.provision :shell, path: 'provision/setup.sh', keep_color: true
    calasisqueta.vm.network :forwarded_port, guest: 80, host: 8000
  end
end
