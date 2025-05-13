const { Builder, By, Key, until } =     require('selenium-webdriver')
const assert = require('assert')


describe('TC-001', function() {
this.timeout(30000)
let driver
let vars

beforeEach(async function() {
  driver = await new Builder().forBrowser('chrome').build()
  vars = {}
})
afterEach(async function() {
  await driver.quit();
})
  it('TC-001', async function() {
    await driver.get("https://dev.tudera.com/")
    await driver.wait(until.elementIsVisible(await driver.findElement(By.css(".peer-checked\\/signup\\3Atext-gray-900"))), 10)
    await driver.findElement(By.css(".peer-checked\\/signup\\3Atext-gray-900")).click()
    await driver.findElement(By.id("email")).sendKeys("preilakos3@gmail.com")
    await driver.findElement(By.css(".bg-blue-600")).click()
{
  const elements = await driver.findElements(By.css(".mt-8"))
  assert(elements.length)
}
  })
})
